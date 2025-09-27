<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('index');
// Route::get('/register', [ProfileController::class, 'create']);
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/verify_notice', [VerifyController::class, 'notice'])->name('verification.notice');
// Route::get('/profile', [ProfileController::class, 'create']);
//Route::post('/login', [ItemController::class, 'index']);
Route::get('/product', [ItemController::class, 'index'])->name('product');
Route::get('/items/{product_id}', [ItemController::class, 'getDetail'])->name('item.detail');
Route::post('/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1']);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('regist_profile');
    // Route::post('/mypage/profile/upload',[ProfileController::class, 'upload']);
    Route::post('/mypage/regist_profile', [ProfileController::class, 'store']);
    Route::post('/product', [ItemController::class, 'index']);
    Route::get('/purchase/{product_id}', [PurchaseController::class, 'show'])->where('product_id', '[0-9]+')->name('purchase');
    Route::get('/purchase/address/{product_id}/{paymentMethod?}', [PurchaseController::class, 'edit'])->name('purchase.address');
    Route::post('/purchase/address', [PurchaseController::class, 'update']);
    Route::post('/purchase/{product_id}', [PurchaseController::class, 'store']);
    Route::post('/items/{product_id}', [ItemController::class, 'comment']);
    Route::post('/like/{product_id}', [ItemController::class, 'toggleLike']);
    Route::get('/sell', [ItemController::class, 'show_sell'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store_sell']);
    Route::post('/sell/upload', [ItemController::class, 'upload_img']);
});

/* メール認証後プロフィール登録画面へ遷移 */
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (!$user) {
        abort(404, 'User not found.');
    }

    // ハッシュ検証（メールアドレスの SHA1 値と比較）
    if (!hash_equals((string) $hash, sha1($user->email))) {
        abort(403, 'Invalid verification link.');
    }

    // 未認証なら認証状態にする
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    // 自動ログイン
    Auth::login($user);

    return redirect('/mypage/profile');  // プロフィール登録画面へ
})->middleware(['signed'])->name('verification.verify');

