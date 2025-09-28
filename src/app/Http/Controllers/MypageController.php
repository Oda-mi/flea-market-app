<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;

class MypageController extends Controller
{
    public function index()
    {
        return view('mypage.index');
    }

    public function profile()
    {
        return view('mypage.profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        return redirect()->route('mypage.index');
    }
}
