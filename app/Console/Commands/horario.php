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
       $correos = ["al221711149@gmail.com","urielongo1069@gmail.com", "al221811726@gmail.com"];
       
        Mail::to($correos)->send(new enviar_recordatorio);
        //log("Holiwis 2");
       
        
       
      
    }

}