<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\enviar_prueba;

class CorreoController extends Controller
{
    public function enviarNuevo(){
        $data = "Hola que tal!!";
        Mail::to('al221711149@gmail.com')->send(new enviar_prueba($data));


    }

}
