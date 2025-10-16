<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{

    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('purchase.index' , compact('item','user'));
    }


    public function store(PurchaseRequest $request, $item_id)
    {
        $user = auth()->user();
        $validated = $request->validated();

        DB::transaction(function () use ($user, $item_id, $validated) {

            $updated = Item::where('id', $item_id)
                            ->where('is_sold', false)
                            ->update(['is_sold' => true]);

            if (!$updated) {
                throw new \Exception();
            }

            Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'payment_method' => $validated['payment_method'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
        ]);
        });

        return redirect()->route('items.index');
    }


    public function address($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('purchase.address',compact('item','user'));
    }


    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = auth()->user();

        $validated = $request->validated();
        $user->update([
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
        ]);

        return redirect()->route('purchase.index', compact('item_id'));
    }
}
