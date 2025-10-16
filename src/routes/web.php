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

/* 未ログインでも表示可能 */
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/items/{product_id}', [ItemController::class, 'getDetail'])->name('item.detail');

/* ログイン済 かつ メール認証済 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('regist_profile');
    Route::post('/mypage/regist_profile', [ProfileController::class, 'store']);
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

/* ログイン済 (メール認証未でも可) */
Route::middleware(['auth'])->group(function () {
    Route::get('/verify_notice', [VerifyController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [RegisteredUserController::class, 'success_verify'])->middleware(['signed'])->name('verification.verify');
    /* メール再送は1分間に6回まで */
    Route::post('/resend', [RegisteredUserController::class, 'resend'])->middleware(['throttle:6,1']);
});
