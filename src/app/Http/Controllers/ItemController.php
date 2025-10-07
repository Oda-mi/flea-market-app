<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('page','recs');

        if ($tab === 'myList'){
            $user = auth()->user();
            //favoritesテーブル作成したら実装する
            //$items = $user->favorites()->with('item')->get()->pluck('item');
        } else {
            $items = Item::keywordSearch($keyword)->get();
        }
        return view('items.index', compact('items','keyword','tab'));
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
