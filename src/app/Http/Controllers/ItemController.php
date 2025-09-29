<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;


class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('items.index',compact('items'));
    }

    //商品詳細
    public function show($item_id)
    {
        $item = Item::with(['condition' , 'categories'])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    //出品
    public function sell()
    {
        $conditions = Condition::all();
        $categories = Category::all();

        return view('items.sell',compact('conditions','categories'));
    }
}
