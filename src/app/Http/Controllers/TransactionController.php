<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionMessageRequest;

use App\Models\Transaction;
use App\Models\TransactionMessage;


use Illuminate\Support\Facades\DB;




class TransactionController extends Controller
{

    public function show(Request $request, $id)
    {
        $transaction = Transaction::with('messages', 'item')->findOrFail($id);

        $user = auth()->user();

        $otherTransactions = $user->buyingTransactions()
                                ->with('item')
                                ->get()
                                ->merge(
                                    $user->sellingTransactions()->with('item')->get()
                                )
                                // 今表示中の取引を除外
                                ->filter(function($otherTransaction) use ($transaction) {
                                    return $otherTransaction->id !== $transaction->id;
                                })
                                // 新着順ソート
                                ->sortByDesc(function($otherTransaction){
                                    return $otherTransaction->latest_message_at ?? $otherTransaction->created_at;
                                });

        return view('transactions.chat', compact('transaction', 'otherTransactions'));
    }


    public function storeMessage(TransactionMessageRequest $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $user = auth()->user();

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user, $transaction, $request) {


            TransactionMessage::create([
                'message'        => $validated['message'],
                'user_id'        => $user->id,
                'transaction_id' => $transaction->id,
            ]);

            $transaction->update([
                'latest_message_at' => now(),
            ]);
        });

        return redirect()->route('transactions.show', $transaction->id);
    }
}
