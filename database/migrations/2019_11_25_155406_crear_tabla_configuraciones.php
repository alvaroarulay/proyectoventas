<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablaConfiguraciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_empresa');
            $table->string('nit');
            $table->string('representante_legal');
            $table->string('direccion');
            $table->string('telefono',20);
            $table->string('email',50);
            $table->string('actividad');
            $table->string('image_name');
            $table->integer('iva');
            $table->integer('ganancia');
            $table->integer('mantenimiento');
            $table->timestamps();
        });
        DB::table('configuraciones')->insert(array('id'=>'1','nombre_empresa'=>'Selabi','nit'=>'6101034014','representante_legal'=>'Alvaro Aruquipa','direccion'=>'Av. Antofagasta Nro. 1026','telefono'=>77510418,'email'=>'alvarolp1@gmail.com','actividad'=>'Telecomunicaciones','image_name'=>'1576201092.jpeg','iva'=>13,'ganancia'=>30,'mantenimiento'=>2500));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuraciones');
    }
}
