<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaComprobantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->biginteger('num_autorizacion')->unsigned()->nullable();
            $table->string('llave',255)->nullable();
            $table->integer('cantidad')->unsigned();
            $table->boolean('estado')->default(1);
            $table->datetime('fecha_lim_emision');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes');
    }
}
