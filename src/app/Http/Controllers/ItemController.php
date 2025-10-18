<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;



class ItemController extends Controller
{
    //トップページ商品一覧画面
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('page','recs');

        if ($tab === 'myList'){
            if (!auth()->check()) {
                $items = collect();
            } else {
                $user = auth()->user();
                $items = $user->favorites()->get();
            }
        } else {
            if(auth()->check()) {
                $items = Item::where(function($query){
                    $query->where('user_id','!=',auth()->id())
                          ->orWhereNull('user_id');
                })
                ->keywordSearch($keyword)
                ->get();
            } else {
                $items = item::keywordSearch($keyword)->get();
            }
        }
        return view('items.index', compact('items','keyword','tab'));
    }

    //商品詳細
    public function show($item_id)
    {
        $item = Item::with([
            'condition',
            'categories',
            'comments.user'
            ])->findOrFail($item_id);

        return view('items.show', compact('item'));
    }

    //コメント投稿処理
    public function storeComment(CommentRequest $request, $item_id)
    {
        $user = auth()->user();
        $validated = $request->validated();

        Comment::create([
            'comment' => $validated['comment'],
            'user_id' => $user->id,
            'item_id' => $item_id,
        ]);

        return redirect()->route('items.show', $item_id);
    }

    //出品画面表示
    public function sell()
    {
        $conditions = Condition::all();
        $categories = Category::all();

        return view('items.sell',compact('conditions','categories'));
    }

    //出品処理
    public function store(ExhibitionRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['img_url'] = basename($imagePath);
        }

        $validated['user_id'] = auth()->id();
        $validated['is_sold'] = 0;

        $item = Item::create([
            'name' => $validated['name'],
            'brand' => $validated['brand'] ?? null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition_id' => $validated['condition_id'],
            'img_url' => $validated['img_url'],
            'user_id' => $validated['user_id'],
            'is_sold' => $validated['is_sold'],
        ]);

        if (isset($validated['categories'])) {
            $item->categories()->attach($validated['categories']);
        }
        return redirect()->route('items.index');
    }

    //いいね機能
    public function toggleFavorite(Item $item)
    {
        $user = auth()->user();

        if ($user->favorites()->where('item_id',$item->id)->exists()) {
            $user->favorites()->detach($item->id);
        } else {
            $user->favorites()->attach($item->id);
        }
        return back();
    }

}