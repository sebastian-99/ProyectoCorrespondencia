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
            $table->text('asunto');
            $table->longText('descripcion');
            $table->dateTime('fecha_creacion');
            $table->text('turno');
            $table->text('comunicado');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->unsignedBigInteger('idtac_tipos_actividades');
            $table->unsignedBigInteger('idar_areas');
            $table->unsignedBigInteger('idu_users');
            $table->boolean('status')->default(1);
            $table->boolean('importancia')->default(1);
            $table->boolean('activo')->default(1);
            $table->text('archivo1', 255)->nullable();
            $table->text('archivo2', 255)->nullable();
            $table->text('archivo3', 255)->nullable();
            $table->text('link1')->nullable();
            $table->text('link2')->nullable();
            $table->text('link3')->nullable();
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
