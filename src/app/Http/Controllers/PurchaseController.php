<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;


class PurchaseController extends Controller
{
    /* 商品購入画面表示 */
    public function show(Request $request, $product_id) {
        /* ユーザ取得 */
        $user = Auth::user();

        /* 商品情報取得 */
        $product = Product::find($product_id);

        /* 支払い方法 */
        $paymentOptions = [
            1 => 'コンビニ支払い',
            2 => 'カード支払い',
        ];
        $paymentMethod = $request->input('payment-method');

        if (is_null($request->zipcode)) {
            $profile = Profile::find($user->id);
        } else {
            $profile = new Profile();
            $profile->zipcode  = $request->zipcode;
            $profile->address  = $request->address;
            $profile->building = $request->building;
        }

        return view('/purchase/buy', compact('product', 'user', 'paymentOptions', 'paymentMethod', 'profile'));
    }

    /* 住所変更ページ表示 */
    public function edit($product_id, $paymentMethod = null) {
        /* ユーザ取得 */
        $user = Auth::user();

        return view('/purchase/address', compact('user', 'product_id', 'paymentMethod'));
    }

    /* 住所変更 */
    public function update(Request $request) {
        /* 購入と取りやめる可能性があるので
           まだ、purchasesテーブルの更新はしない */

        /* バリデーション */
        $request->validate([
            'zipcode' => 'required | min:8 | max:8',
            'address' => 'required',
            'building' => 'nullable | string',
        ],
        [
            'zipcode.required' => '郵便番号を入力してください',
            'zipcode.min'      => '郵便番号は8文字で入力してください',
            'zipcode.max'      => '郵便番号は8文字で入力してください',
            'address.required' => '住所を入力してください',
        ]);

        /* ユーザ取得 */
        $user = Auth::user();

        /* 商品情報取得 */
        $product = Product::find($request->product_id);

        /* 支払い方法 */
        $paymentOptions = [
            1 => 'コンビニ支払い',
            2 => 'カード支払い',
        ];
        $paymentMethod = $request->paymentMethod;

        $profile = Profile::find($user->id);
        $profile->zipcode = $request->zipcode;
        $profile->address = $request->address;
        $profile->building = $request->building;

        return view('/purchase/buy', compact('product', 'user', 'paymentOptions', 'paymentMethod', 'profile'));
    }

    /* 商品購入処理 */
    public function store(Request $request, $product_id) {
        /* ユーザ取得 */
        $user = Auth::user();

        /* 商品購入テーブルを更新 */
        $purchase = new Purchase();
        $purchase->product_id = $product_id;
        $purchase->user_id = $user->id;
        $purchase->payment_method =$request->paymentMethod;
        $purchase->zipcode = $request->zipcode;
        $purchase->address = $request->address;
        $purchase->building = $request->building;
        $purchase->save();

        // return view('purchase.payment');

        /* ===== 決済処理 ===== */
        /* セキュリティキー取得 */
        Stripe::setApiKey(config('services.stripe.secret'));

        /* 支払い方法 */
        if ($request->paymentMethod === '2') {
            $paymentMethod = 'card';
        } else {
            $paymentMethod = 'konbini';
        }

        /* 商品情報 */
        $lineItems = [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $request->name,
                ],
                'unit_amount' => $request->price,
            ],
            'quantity' => 1,
        ]];

        /* Stripe Checkout Session 作成 */
        $session = CheckoutSession::create([
            'payment_method_types' => [$paymentMethod],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('index'),
            'cancel_url' => route('index'),
            // 'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            // 'cancel_url' => route('checkout.cancel'),
        ]);
        
        return redirect($session->url);
    }
}
