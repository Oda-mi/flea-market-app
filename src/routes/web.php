<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/item/{item_id}',[ItemController::class, 'show'])->name('items.show');


Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view ('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware(['auth','throttle:6,1'])->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('mypage.profile');
    })->middleware(['auth', 'signed'])->name('verification.verify');


    Route::get('/mypage',[MypageController::class,'index'])->name('mypage.index');
    Route::get('/mypage/profile',[MypageController::class,'profile'])->name('mypage.profile');
    Route::put('/mypage/profile',[MypageController::class,'update'])->name('mypage.update');


    Route::get('/purchase/{item_id}',[PurchaseController::class, 'index'])->name('purchase.index');


    Route::get('/purchase/address/{item_id}',[PurchaseController::class,'address'])->name('purchase.address');
    Route::put('/purchase/address/{item_id}',[PurchaseController::class,'updateAddress'])->name('purchase.updateAddress');


    Route::get('/sell', [ItemController::class, 'sell'])->name('items.sell');
    Route::post('sell',[ItemController::class, 'store'])->name('items.store');


    Route::post('item/{item_id}/comment',[ItemController::class,'storeComment'])->name('comment.store');


    Route::post('/items/{item}/favorite', [ItemController::class, 'toggleFavorite'])->name('items.toggleFavorite');


    Route::post('/stripe/checkout/{item_id}', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel/{item_id}', [StripeController::class, 'cancel'])->name('stripe.cancel');



    Route::get('/transactions/{id}',
        [TransactionController::class, 'show'])
        ->name('transactions.show');

    Route::post('/transactions/{id}/messages',
        [TransactionController::class, 'storeMessage'])
        ->name('transactions.storeMessages');

    Route::delete('/transactions/messages/{messageId}',
        [TransactionController::class, 'destroyMessage'])
        ->name('transactions.destroyMessages');

    Route::patch('/transactions/messages/{messageId}',
        [TransactionController::class, 'updateMessage'])
        ->name('transactions.updateMessages');



});

