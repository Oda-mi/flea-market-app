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

        return view('purchase.index' , compact('item'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        return redirect()->route('items.index');
    }

    public function address($item_id)
    {
        return view('purchase.address');
    }
}
