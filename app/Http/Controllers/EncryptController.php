<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Brs\FunctionPkg;

class EncryptController extends Controller
{
    public function index()
    {
        $new = new FunctionPkg;

        /*
         * Orden de inserción de firma electronica
         * id_actividad (act)
         * id_usuario (id)
         * nombre_usuario (ini)
         */

        $encrypt = $new->Encrypt(12,12,'Alberto');

        $decrypt = $new->Decrypt( $encrypt );

        $arr = array( 
                        'act' => $decrypt->act, 
                        'id' =>  $decrypt->id, 
                        'ini' => $decrypt->ini, 
                        'encrypt' => $encrypt 
                    );

        return $arr;
    }
}
