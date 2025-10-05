<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        //在庫確認
        if ($item->is_sold) {
            return redirect()->route('items.index');
        }
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);
        //sold表示させる
        $item->update(['is_sold' => true]);

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

        $user->update($request->only(['postal_code','address','building']));

        return redirect()->route('purchase.index', compact('item_id'));
    }
}
