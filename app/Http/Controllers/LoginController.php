<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ValidarLogin;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function login(Requests $request)
    {
    
        $email->email = $request->get('email');
        $password->password = $request->get('password');

        return view('login');
    }
    
   
}
