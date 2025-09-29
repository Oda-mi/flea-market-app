<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

use App\Models\Item;

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
