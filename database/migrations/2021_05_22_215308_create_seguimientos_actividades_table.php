<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguimientosActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientos_actividades', function (Blueprint $table) {
            $table->bigIncrements('idseac');
            $table->unsignedBigInteger('idreac_responsables_actividades');
            $table->dateTime('fecha');
            $table->longText('detalle');
            $table->float('porcentaje');
            $table->string('estado');
            $table->timestamps();
            $table->foreign('idreac_responsables_actividades')->references('idreac')->on('responsables_actividades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguimientos_actividades');
    }
}
