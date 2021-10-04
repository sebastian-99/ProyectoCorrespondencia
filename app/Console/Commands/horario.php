<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
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

        $user = DB::SELECT("SELECT email FROM users WHERE idu = 15");


        $texto = "[". date ("Y-m-d H:i:s") . "]: Porfavor dios";
        Storage::append('archivo.txt', $texto);
        Mail::to($user)->send('este es un mensaje');
        Mail::send('mail', $data, function ($message) use ($data) {
            $message->from('green.zone.products1@gmail.com', 'GreenZone.');            // de parte de quien.
            $message->to($data['correo'], $data['nombre']);
            $message->subject($data['asunto']);                // para quien recibe.
        });
    }

}