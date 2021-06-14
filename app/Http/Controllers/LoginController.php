<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(Request $reques)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        return view('login');
    }
}
