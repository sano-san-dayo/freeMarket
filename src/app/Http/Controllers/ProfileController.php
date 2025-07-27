<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function create()
    {
        return view('profile');
    }

    public function store(Request $request)
    {
        dd(request);
        // バリデーション & 保存処理
        // プロフィール登録処理
        $request->validate([
            'name' => 'required|string|max:255',
            'zipcode' => 'required',
            'address' => 'required',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->zipcode = $reques->zipcode;
        $user->address = $request->address;
        $user->building = $request->building; 

        $user->save();

        return redirect()->route('home')->with('status', 'プロフィール登録が完了しました');
    }
    // public function create() {
    //     return view('regist_user');
    // }

    // public function store(Request $request) {
    //     $data = $request->only([
    //         'name',
    //         'email',
    //         'password',
    //     ]);

    //     dd($data);
    // }
}
