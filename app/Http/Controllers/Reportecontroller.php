<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\actividades;
use App\Models\User;

class Reportecontroller extends Controller
{
    public function reporte (){
       
        return view('Reporte');
    }

    public function getReportes(Request $request)
    {
        if ($request->ajax()) {
            $data = actividades::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Detalles</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

}
