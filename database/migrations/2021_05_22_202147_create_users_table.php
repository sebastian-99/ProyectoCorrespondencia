<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('idu');
            $table->unsignedBigInteger('idtu_tipos_usuarios');
            $table->text('imagen')->nullable();
            $table->string('titulo');
            $table->string('nombre');
            $table->string('app');
            $table->string('apm');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('idar_areas');
            $table->boolean('activo')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('idtu_tipos_usuarios')->references('idtu')->on('tipos_usuarios');
            $table->foreign('idar_areas')->references('idar')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
