<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\TransactionMessageRequest;
use App\Models\Transaction;
use App\Models\TransactionMessage;


class TransactionController extends Controller
{

    public function show(Request $request, $id)
    {
        $transaction = Transaction::with('messages', 'item')->findOrFail($id);

        $user = auth()->user();

        $transaction->messages()
                    ->where('user_id', '<>', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        $otherTransactions = $user->buyingTransactions()
                                ->with('item')
                                ->get()
                                ->merge(
                                    $user->sellingTransactions()->with('item')->get()
                                )
                                ->filter(function($otherTransaction) use ($transaction) {
                                    return $otherTransaction->id !== $transaction->id;
                                })
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

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('transaction_images', 'public');
            }

            TransactionMessage::create([
                'message'        => $validated['message'],
                'image_path'     => $imagePath,
                'user_id'        => $user->id,
                'transaction_id' => $transaction->id,
            ]);

            $transaction->update([
                'latest_message_at' => now(),
            ]);
        });

        return redirect()->route('transactions.show', $transaction->id);
    }


    public function destroyMessage(Request $request, $messageId)
    {
        $message = TransactionMessage::findOrFail($messageId);

        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->back();
    }


    public function updateMessage(TransactionMessageRequest $request, $messageId)
    {
        $message = TransactionMessage::findOrFail($messageId);

        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($message, $validated) {

            $message->update([
                'message' => $validated['message'],
            ]);

            $message->transaction->update([
                'latest_message_at' => now(),
            ]);
        });

        return redirect()->back();
    }
}
