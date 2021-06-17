<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidarLogin;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function login(ValidarLogin $request)
    {
    
        return 'hola';
    }
    
   
}
