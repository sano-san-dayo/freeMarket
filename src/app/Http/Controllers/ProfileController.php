<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Http\Requests\ProfileRequest;

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
    public function edit() {
        /* ユーザ情報取得 */
        $user = Auth::user();

        /* プロフィール情報取得 */
        $profile = Profile::where('user_id', $user->id)->first();
        if (is_null($profile)) {
            $fileName = null;
        } else {
            $fileName = $profile->image;
        }

        return view('mypage.regist_profile', compact('user', 'profile', 'fileName'));
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
        if (!in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
            return back()->withErrors(['image-file' => '対応していないファイル形式です']);
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
    public function store(ProfileRequest $request)
    {
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

        return redirect()->route('mypage')->with('status', 'プロフィール登録が完了しました');
    }
}
