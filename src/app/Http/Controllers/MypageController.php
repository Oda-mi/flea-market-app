<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('mypage.index',compact('user'));
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
        $user->update($request->only(['name','postal_code','address','building']));

        return redirect()->route('mypage.index');
    }
}
