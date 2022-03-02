<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivosSeguimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivos_seguimientos', function (Blueprint $table) {
            $table->bigIncrements('idarseg');
            $table->unsignedBigInteger('idseac_seguimientos_actividades');
            $table->string('nombre');
            $table->string('ruta');
            $table->longText('detalle');
            $table->timestamps();
            $table->foreign('idseac_seguimientos_actividades')->references('idseac')->on('seguimientos_actividades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivos_seguimientos');
    }
}
