<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\User;
use App\Http\Requests\ItemRequest;

class ItemController extends Controller
{
    /* 商品一覧画面表示 */
    public function index(Request $request) {

        /* 検索キーワード取得 */
        $keyword = $request->input('keyword');

        /* ユーザ取得 */
        $user = Auth::user();

        /* 初期表示するタブを設定 */
        if ($keyword) {
            $tab = $request->get('tab', 'recommend');
        } else {
            /* keywordが未指定の場合、ログイン有無で初期タブを変更 */
            $tab = $request->query('tab', $user ? 'mylist' : 'recommend');
        }

        if (!$user && $tab === 'mylist') {
            /* 空のコレクション */
            $products = collect();
        } elseif ($user && $tab === 'mylist') {
            /* マイリスト */
            $products = $user->likedProducts()->where('name', 'like', "%{$keyword}%")->get();
        } elseif ($user && $tab === 'recommend') {
            /* おすすめ */
            $products = Product::where('user_id', '!=', $user->id)->where('name', 'like', "%{$keyword}%")->get();
        } else {
            /* おすすめ(全製品) */
            $products = Product::where('name', 'like', "%{$keyword}%")->get();
        }

        $purchases = Purchase::all();
    
        return view('product', compact('products', 'tab', 'keyword', 'purchases'));
    }

    /* 商品詳細画面表示 */
    public function getDetail(Request $request, $product_id) {
        /* ユーザ取得 */
        $user = Auth::user();

        // $product = Product::with([
        //     'comments.user.profile'
        // ])->findOrFail($product_id);
        $product = Product::with(['comments' => function($query) {
            $query->orderBy('id', 'asc');
        }, 'comments.user.profile'])->find($product_id);
        $categories = $product->categories;
        $condition = Condition::find($product->condition)->name;
        $like_count = Like::where('product_id', $product_id)->count();
        $comments = Comment::where('product_id', $product_id)->orderBy('created_at', 'desc')->get();
        
        if ($user) {
            if (Like::where('product_id', $product_id)->where('user_id', $user->id)->count() > 0) {
                $liked = 1;
            } else {
                $liked = 0;
            }
        } else {
            $liked = 0;
        }

        $purchase = Purchase::where('product_id', $product_id)->get();
    
        /* 商品詳細画面のボタン制御用 */
        $from = $request->query('from');
        if ($from === 'sale') {
            /* 出品した商品の場合 */
            $button = 1;
        } elseif ($from === 'purchase') {
            /* 購入した商品の場合 */
            $button = 2;
        } else {
            $button = 0;
        }

        return view('detail', compact('product', 'condition', 'categories', 'like_count', 'liked', 'comments', 'purchase', 'button'));
    }

    /* コメント登録 */
    public function comment(ItemRequest $request, $product_id) {
        /* ユーザ取得 */
        $user = Auth::user();
        
        /* コメント登録 */
        $data = array();
        $data['content'] = $request->comment;
        $data['product_id'] = $product_id;
        $data['user_id'] = $user->id;
        Comment::create($data);

        
        $product = Product::with([
            'comments.user.profile'
        ])->findOrFail($product_id);
        $condition = Condition::find($product->condition)->name;
        $categories = $product->categories;
        $like_count = Like::where('product_id', $product_id)->count();
        $comments = Comment::where('product_id',$product_id)->orderBy('created_at', 'desc')->get();
        // $comment_users = User::where('id', $comments->user_id)->get();

        if ($user) {
            if (Like::where('product_id', $product_id)->where('user_id', $user->id)) {
                $liked = 1;
            } else {
                $liked = 0;
            }
        } else {
            $liked = 0;
        }

        $purchase = Purchase::where('product_id', $product_id)->get();
        $button = 0;

        return view('detail', compact('product', 'condition', 'categories', 'like_count', 'liked', 'comments', 'purchase', 'button'));
    }

    /* いいねボタン押下 */
    public function toggleLike(Request $request, $product_id) {
        /* ユーザ取得 */
        $user = Auth::user();
 
        if ($request->liked === "1") {
            /* いいね を削除 */
            Like::where('product_id', $product_id)->where('user_id', $user->id)->delete();
            $liked = 0;
        } else {
            /* いいね を追加 */
            Like::create([
                'product_id' => $product_id,
                'user_id' => $user->id,
            ]);
            $liked = 1;
        }

        $product = Product::with([
            'comments.user.profile'
        ])->findOrFail($product_id);
        $condition = Condition::find($product->condition)->name;
        $categories = $product->categories;
        $like_count = Like::where('product_id', $product_id)->count();
        $comments = Comment::where('product_id', $product_id)->orderBy('created_at', 'desc')->get();

        $purchase = Purchase::where('product_id', $product_id)->get();
        $button = 0;

        return view('detail', compact('product', 'condition', 'categories', 'like_count', 'liked', 'comments', 'purchase', 'button'));
    }

    /* 商品出品画面  */
    public function show_sell() {
        /* カテゴリー取得 */
        $categories = Category::all();

        /* 商品の状態 */
        $conditions = $this->setConditions();

        $fileName = null;

        return view('sell', compact('categories', 'conditions', 'fileName'));
    }

    /* 商品出品実施 */
    public function store_sell(Request $request) {

        /* ユーザ情報取得 */
        $user = Auth::user();

        /* 商品テーブル用データ設定 */
        $product = new Product();
        $product->user_id = $user->id;
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->condition = $request->condition;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $request->image;

        /* 商品テーブル更新 */
        $product->save();

        /* 商品-カテゴリー 中間テーブル更新 */
        $product->categories()->sync($request->input('categories'));

        /* 商品一覧画面表示用の各種情報設定 */
        $keyword = null;
        $products = $user->likedProducts()->where('name', 'like', "%{$keyword}%")->get();
        $tab = 'mylist';
        $purchases = Purchase::all();
    
        return view('product', compact('products', 'tab', 'keyword', 'purchases'));
    }

    /* 商品画像設定 */
    public function upload_img(Request $request) {
        /* 表示の更新のみでDB登録・更新は行わない */

        /* ディレクトリ名 */
        $dir = 'images/items';

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

        /* カテゴリー取得 */
        $categories = Category::all();

        /* 商品の状態 */
        $conditions = $this->setConditions();

        return view('sell', compact('categories', 'conditions', 'fileName'));
    }

    private function setConditions(): array {
        return [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];
    }
}
