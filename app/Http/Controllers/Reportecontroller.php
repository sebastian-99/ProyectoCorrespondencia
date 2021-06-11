<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Actividades as actividades;

use Illuminate\Support\Facades\DB;

class Reportecontroller extends Controller
{

    public function Detalles($idac){
        $query = actividades::find($idac)
                ->join('users','actividades.idu_users','=','users.idu')
        ->join('responsables_actividades','actividades.idac','=','responsables_actividades.idac_actividades')
        ->join('areas','users.idar_areas','=','areas.idar')
        ->where('idac_actividades','=',$idac)
        ->get();
        return response()->json($query);
    }

    public function reporte(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('actividades')
            ->join('users','actividades.idu_users','=','users.idu')
           
            ->get();
           
         
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->idac.'" data-original-title="Detalles"
                     class="edit btn btn-success btn-sm Detalles">Detalles</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('reporte');
    }


}
