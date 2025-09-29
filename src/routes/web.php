<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;


//誰でも見れる//
// トップページ
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 商品詳細
Route::get('/item/{item_id}',[ItemController::class, 'show'])->name('items.show');



//ログイン必須 ミドルウェア設定
Route::middleware('auth')->group(function () {

//マイページ
Route::get('/mypage',[MypageController::class,'index'])->name('mypage.index');

//マイページ編集
Route::get('/mypage/profile',[MypageController::class,'profile'])->name('mypage.profile');

//マイページ更新
Route::put('/mypage/profile',[MypageController::class,'update'])->name('mypage.update');

//商品購入
Route::get('/purchase/{item_id}',[PurchaseController::class, 'index'])->name('purchase.index');

Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

//住所変更画面
Route::get('/purchase/address/{item_id}',[PurchaseController::class,'address'])->name('purchase.address');

Route::put('/purchase/address/{item_id}',[PurchaseController::class,'updateAddress'])->name('purchase.updateAddress');

//出品
Route::get('/sell', [ItemController::class, 'sell'])->name('items.sell');

});






// メール認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
});
