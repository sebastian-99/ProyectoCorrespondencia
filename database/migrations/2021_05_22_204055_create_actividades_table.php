<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->bigIncrements('idac');
            $table->string('asunto');
            $table->longText('descripcion');
            $table->dateTime('fecha_creacion');
            $table->string('turno');
            $table->string('comunicado');
            $table->dateTime('fecha_inicio');
            $table->timestamp('hora_inicio');
            $table->dateTime('fecha_fin');
            $table->timestamp('hora_fin');
            $table->unsignedBigInteger('idtac_tipos_actividades');
            $table->unsignedBigInteger('idar_areas');
            $table->unsignedBigInteger('idu_users');
            $table->timestamp('finalizado_en');
            $table->string('status');
            $table->string('importancia');
            $table->string('activo');
            $table->string('archivo1', 255)->nullable();
            $table->string('archivo2', 255)->nullable();
            $table->string('archivo3', 255)->nullable();
            $table->string('link1')->nullable();
            $table->string('link2')->nullable();
            $table->string('link3')->nullable();
            $table->timestamps();
            $table->foreign('idtac_tipos_actividades')->references('idtac')->on('tipos_actividades');
            $table->foreign('idar_areas')->references('idar')->on('areas');
            $table->foreign('idu_users')->references('idu')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}
