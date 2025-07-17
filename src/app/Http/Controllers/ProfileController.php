<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function create() {
        return view('regist_user');

    }

    public function store(Request $request) {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        dd($data);
    }
}
