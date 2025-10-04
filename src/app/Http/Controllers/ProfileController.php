<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class ProfileController extends Controller
{
    /* プロフィール画面表示 */
    public function show(Request $request) {
        /* ユーザ取得 */
        $user = Auth::user();

        /* プロファイル取得 */
        $profile = Profile::where('user_id', $user->id)->first();

        /* 初期表示するタブを設定 */
        $tab = $request->get('tab', 'sale');

        /* タブに表示するデータ取得 */
        if ($tab === 'sale') {
            /* 出品した商品 */
            $products = Product::where('user_id', $user->id)->get();
        } else {
            /* 購入した商品 */
            $products = Purchase::where('user_id', $user->id)->get()->pluck('product');
        }

        return view('/mypage/profile', compact('user', 'profile', 'tab', 'products'));
    }
    
    /* プロフィール編集画面表示 */
    public function edit(Request $request) {
        /* ユーザ情報取得 */
        $user = Auth::user();

        /* プロフィール情報取得 */
        $profile = Profile::where('user_id', $user->id)->first();
        if (is_null($profile)) {
            $fileName = null;
        } else {
            $fileName = $profile->image;
        }

        $redirect_from = $request->redirect_from;

        return view('mypage.regist_profile', compact('user', 'profile', 'fileName', 'redirect_from'));
    }

    /* ユーザ画像更新 */
    public function upload(Request $request) {
        /* 表示の更新のみでDB登録・更新は行わない */

        /* ディレクトリ名 */
        $dir = 'images/profile';

        /* ユーザ取得 */
        $user = Auth::user();

        /* バリデーション */
        $request->validate([
            'image-file' =>  'required | file | max:2048',
        ]);
        $ext = $request->file('image-file')->getClientOriginalExtension();
        if (!in_array(strtolower($ext), ['jpg','jpeg','png'])) {
            return back()->withErrors(['image-file' => '「.png」または「.jpg」形式でアップロードしてください']);
        }
    
        /* 画像ファイルを保存 */
        $file = $request->file('image-file');
        $fileName = $file->getClientOriginalName();
        Storage::disk('public')->putFileAs($dir, $file, $fileName);

        /* プロフィール情報取得 */
        $profile = Profile::where('user_id', $user->id)->first();

        return view('mypage.regist_profile', compact('user', 'profile', 'fileName'));
    }

    /* プロフィール情報登録・更新 */
    public function store(Request $request)
    {
        /* 画像ファイル選択時 */
        if ($request->hasFile('image-file')) {
            /* 表示の更新のみでDB登録・更新は行わない */

            /* ディレクトリ名 */
            $dir = 'images/profile';

            /* ユーザ取得 */
            $user = Auth::user();

            /* バリデーション */
            $request->validate([
                'image-file' =>  'required | file | max:2048',
            ]);
            $ext = $request->file('image-file')->getClientOriginalExtension();
            if (!in_array(strtolower($ext), ['jpg','jpeg','png'])) {
                return back()->withInput()->withErrors(['image-file' => '「.png」または「.jpg」形式でアップロードしてください']);
            }
        
            /* 画像ファイルを保存 */
            $file = $request->file('image-file');
            $fileName = $file->getClientOriginalName();
            Storage::disk('public')->putFileAs($dir, $file, $fileName);

            return back()->withInput()->with('fileName', $fileName);
        }

        /* 「更新する」ボタン押下時 */
        if ($request->action === 'update') {
            /* バリデーションエラーに備えてファイル名をセッションへ保存 */
            session()->put('fileName', $request->fileName);

            /* バリデーション */
            $request->validate([
                'userName' => 'required | string | max:255',
                'zipcode'  => 'required | string | size:8',
                'address'  => 'required | string | max:255',
                'building' => 'nullable | string | max:255',
            ]);

            /* ユーザ名設定 */
            $user = auth()->user();
            $user->name = $request->userName;

            /* プロフィール情報設定 */
            $profile = Profile::where('user_id', $user->id)->first();
            if (is_null($profile)) {
                $profile = new Profile();
            }
            $profile->user_id = $user->id;
            $profile->zipcode = $request->zipcode;
            $profile->address = $request->address;
            $profile->building = $request->building; 
            $profile->image = $request->fileName;

            /* DB登録・更新 */
            $user->save();
            $profile->save();

            /* セッションに保存した画像ファイル名を削除 */
            session()->forget('fileName');

            if ($request->redirect_from === 'profile') {
                return redirect()->route('mypage')->with('status');
            } else {
                return redirect()->route('index');
            }
        }
    }
}
