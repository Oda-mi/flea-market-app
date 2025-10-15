<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->query('page', 'sell');

        $purchasedItems = collect();
        $sellItems = collect();

        if ($tab === 'buy') {
            $purchasedItems = $user->purchases()->with('item')->get()->pluck('item');
        }
        else {
            $sellItems = $user->items()->get();
        }
        $itemsToShow = $tab === 'sell' ? $sellItems : $purchasedItems;

        return view('mypage.index',compact('user','tab','purchasedItems','sellItems','itemsToShow'));
    }


    public function profile()
    {
        $user = auth()->user();

        return view('mypage.profile',compact('user'));
    }


    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profile_images','public');

        $user->profile_image = $imagePath;
        }
        $validated = $request->validated();
        $user->update([
            'name' => $validated['name'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        return redirect()->route('mypage.index');
    }
}
