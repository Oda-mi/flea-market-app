<?php

use Illuminate\Support\Facades\Route;


// トップページ
Route::get('/', function () {
    return view('items.items-index');
});

// 会員登録
Route::get('/register', function () {
    return view('auth.register');
});

// ログイン
Route::get('/login', function () {
    return view('auth.login');
});

// 商品詳細
Route::get('/item/{item}', function ($item) {
    return view('items.items-show', ['item' => $item]);
});

// 商品購入
Route::get('/purchase/{item}', function ($item) {
    return view('purchase.purchase-index', ['item' => $item]);
});

// 送付先住所変更
Route::get('/purchase/address/{item}', function ($item) {
    return view('purchase.purchase-address', ['item' => $item]);
});

// 出品
Route::get('/sell', function () {
    return view('items.items-sell');
});

// マイページ
Route::get('/mypage', function () {
    return view('mypage.mypage-index');
});

// プロフィール編集
Route::get('/mypage/profile', function () {
    return view('mypage.mypage-profile');
});

Route::get('/item', function () {
    $item = [
        'name' => 'ダミー商品名',
        'brand' => 'ダミーブランド',
        'price' => '12345'
    ];
    return view('items.items-show', compact('item'));
});

// 商品購入（ダミー）
Route::get('/purchase/{item}', function ($item) {
    return view('purchase.purchase-index', [
        'item' => [
            'id' => $item,
            'name' => 'ダミー商品',
            'price' => 12345,
            'image' => 'Coffee.jpg'
        ]
    ]);
});

// メール認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
});
