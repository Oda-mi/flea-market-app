<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Evaluation;



class EvaluationController extends Controller
{
    public function store(Request $request, $transaction_id)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        $transaction = Transaction::findOrFail($transaction_id);

        $user = auth()->user();

        if ($user->id === $transaction->buyer_id) {
            // 購入者 → 出品者
            $evaluateeId = $transaction->seller_id;

            // 取引完了に更新
            $transaction->update([
                'status' => 'completed',
            ]);

        } elseif ($user->id === $transaction->seller_id) {
            // 出品者 → 購入者
            $evaluateeId = $transaction->buyer_id;

        } else {
            abort(403);
        }

        $alreadyEvaluated = Evaluation::where('transaction_id', $transaction->id)
            ->where('evaluator_id', $user->id)
            ->exists();

        if ($alreadyEvaluated) {
            return redirect()->route('items.index');
        }

        Evaluation::create([
            'transaction_id' => $transaction->id,
            'evaluator_id'   => $user->id,
            'evaluatee_id'   => $evaluateeId,
            'rating'         => $request->rating,
        ]);

        return redirect()->route('items.index');

    }
}
