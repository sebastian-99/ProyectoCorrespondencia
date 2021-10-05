<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\enviar_recordatorio;
use DB;



class horario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horario:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorio de actividad';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   

        //$user = DB::SELECT("SELECT email FROM users WHERE idu = 15");

        //$texto = "[". date ("Y-m-d H:i:s") . "]: Porfavor dios ayudame";
        $consulta = DB::select("SELECT res.idu_users, CONCAT(us.titulo,' ', us.nombre, ' ', us.app, ' ', us.apm) AS nombre,ac.`fecha_fin`, 
        TIMESTAMPDIFF (DAY, CURDATE(), ac.`fecha_fin` )  AS resultado,
            ac.asunto, res.estado_act, res.idreac, us.email
            FROM responsables_actividades AS res
            JOIN actividades AS ac ON ac.idac = res.idac_actividades
            JOIN users AS us ON us.idu = res.idu_users
            LEFT JOIN seguimientos_actividades AS seg ON seg.idreac_responsables_actividades = res.idreac
            WHERE res.estado_act IS NULL
    ");
        foreach($consulta as $c){
            if($c->resultado == 3){
                Mail::to($c->email)->send(new enviar_recordatorio);
            }
        }
    }

}