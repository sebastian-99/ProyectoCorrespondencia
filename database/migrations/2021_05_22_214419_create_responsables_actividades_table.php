<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsablesActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsables_actividades', function (Blueprint $table) {
            $table->bigIncrements('idreac');
            $table->unsignedBigInteger('idu_users');
            $table->unsignedBigInteger('idac_actividades');
            $table->string('frima');
            $table->boolean('acuse')->default(1);
            $table->dateTime('fecha');
            $table->timestamps();
            $table->foreign('idu_users')->references('idu')->on('users');
            $table->foreign('idac_actividades')->references('idac')->on('actividades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsables_actividades');
    }
}
