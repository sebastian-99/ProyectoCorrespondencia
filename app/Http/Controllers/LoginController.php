<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login()
    {
        return view('login');
    }
    /*public Function validar(Request $request)
    {
        $this->validate($request[
            
            'email' => 'required',
            'password' => 'required',
        ]);
    }*/
}
