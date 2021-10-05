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
        //Storage::append('archivo.txt', $texto);
        $consult = DB::SELECT("SELECT res.idu_users, CONCAT(us.titulo,' ', us.nombre, ' ', us.app, ' ', us.apm)
        AS nombre_us, us.email,ac.idac, res.acuse, res.idreac, seg.estado, CURDATE() AS f_actual,
        MAX(seg.porcentaje) AS porcentaje, ac.fecha_inicio,ac.fecha_fin, us.idtu_tipos_usuarios,ac.asunto
        FROM responsables_actividades AS res
        JOIN actividades AS ac ON ac.idac = res.idac_actividades
        JOIN users AS us ON us.idu = res.idu_users
        JOIN areas AS ar ON ar.idar = us.idar_areas
        LEFT JOIN seguimientos_actividades AS seg ON seg.idreac_responsables_actividades = res.idreac
        WHERE seg.estado = 'Pendiente'
        GROUP BY idu_users;
        ");
        Mail::to($consult[0]->email)->send(new enviar_recordatorio);
        log("Holiwis 2");
      
    }

}