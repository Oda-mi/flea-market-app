<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Item;
use App\Models\Purchase;


class StripeController extends Controller
{
    public function checkout(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();
        $method = $request->input('payment_method', 'credit');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        return DB::transaction(function() use ($item_id, $validated, $method)
        {
            $item = Item::where('id', $item_id)->lockForUpdate()->firstOrFail();

            if ($item->is_sold) {
                throw new \Exception('Item is sold out');
            }
            $paymentMethods = match($method) {
            'credit' => ['card'],
            'convenience' => ['konbini'],
            default => ['card'],
        };

        session([
            'purchase_data' => $validated,
            'payment_method' => $method,
        ]);

        $session = Session::create([
            'payment_method_types' => $paymentMethods,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel', ['item_id' => $item->id]),
            'metadata' => [
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $method,
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
            ],
        ]);
        return redirect($session->url);
        });
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception) {
            return response('Invalid', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            DB::transaction(function () use ($session) {
                $user_id = $session->metadata->user_id;
                $item_id = $session->metadata->item_id;

                $item = Item::where('id', $item_id)->lockForUpdate()->firstOrFail();

                if ($item->is_sold) {
                throw new \Exception('Item is sold out');
                }

                $item->update(['is_sold' => true]);

                Purchase::create([
                    'user_id' => $user_id,
                    'item_id' => $item_id,
                    'payment_method' => $session->metadata->payment_method,
                    'postal_code' => $session->metadata->postal_code,
                    'address' => $session->metadata->address,
                    'building' => $session->metadata->building,
                ]);
            });
        }
        return response('Webhook handled', 200);
    }

    public function success()
    {
        return redirect()->route('items.index');
    }

    public function cancel($item_id)
    {
    return redirect()->route('purchase.index', $item_id);
    }
}
