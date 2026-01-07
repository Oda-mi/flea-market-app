<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Models\TransactionMessage;


class MypageController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('page', 'sell');

        $purchasedItems = collect();
        $sellItems = collect();
        $transactions = collect();

        // ナビ用全取引の未読合計を取得
        $totalUnreadCount = TransactionMessage::whereHas('transaction', function($transactionQuery) use ($user) {
            $transactionQuery->where('buyer_id', $user->id)
                            ->orWhere('seller_id', $user->id);
        })
        ->where('user_id', '<>', $user->id)
        ->where('is_read', false)
        ->count();

        // タブごとの処理
        if ($tab === 'buy') {
            $purchasedItems = $user->purchases()->with('item')->get()->pluck('item');
        }
        elseif ($tab === 'trading') {
             // 購入者・販売者の取引を両方取得
            $transactions = $user->buyingTransactions()
                                ->with(['item', 'messages'])
                                ->get()
                                ->merge(
                                    $user->sellingTransactions()->with(['item', 'messages'])->get()
                                )
                                // 新着順にソート（最新メッセージが上）
                                ->sortByDesc(function($transaction){
                                    return $transaction->latest_message_at ?? $transaction->created_at;
                                });

            // 商品ごとの未読件数を計算
            foreach ($transactions as $transaction) {
                $transaction->unread_count = $transaction->messages
                                            ->where('user_id', '<>', $user->id)
                                            ->where('is_read', false)
                                            ->count();
            }
        }
        else {
            $sellItems = $user->items()->get();
        }
        $itemsToShow = $tab === 'sell' ? $sellItems : $purchasedItems;

        return view('mypage.index',
                    compact(
                        'user',
                        'tab',
                        'purchasedItems',
                        'sellItems',
                        'itemsToShow',
                        'transactions',
                        'totalUnreadCount',
                    ));
    }


    public function profile()
    {
        $user = auth()->user();

        return view('mypage.profile',compact('user'));
    }


    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profile_images','public');

        $user->profile_image = $imagePath;
        }
        $validated = $request->validated();
        $user->update([
            'name' => $validated['name'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
        ]);

        return redirect()->route('mypage.index');
    }
}
