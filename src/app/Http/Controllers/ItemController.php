<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Like; 

class ItemController extends Controller
{
    // public function index() {
    //     return view('product');
    // }
    public function index(Request $request) {

        $user = Auth::user();

        /* 初期表示するタブを設定 */
        $tab = $request->query('tab', $user ? 'mylist' : 'recommend');

        if (!$user && $tab === 'mylist') {
            /* 空のコレクション */
            $products = collect();
        } elseif ($user && $tab === 'mylist') {
            // $products = $user->likes()->with('likeByUsers')->get();
            // $products = $user->likes()->latest();
            // dd($products);
            // $likes = Like::where('user_id', $user->id)->get();
            // dd($likes);
            $products = $user->likedProducts()->get();
            // dd($products);
        } elseif ($user && $tab === 'recommend') {
            $products = Product::where('user_id', '!=', $user->id)->get();
        } else {
            $products = Product::all();
        }

        return view('product', compact('products', 'tab'));
    }
}
