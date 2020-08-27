<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned();
            $table->integer('idmarca')->unsigned();
            $table->integer('idunidad')->unsigned();
            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 100)->unique();
            $table->string('image')->nullable();
            $table->string('descripcion', 256)->nullable();
            $table->boolean('condicion')->default(1);
            $table->integer('stock')->nullable()->default(0);
            $table->decimal('precio',11,2)->nullable()->default(0);
            $table->timestamps();
            $table->foreign('idcategoria')->references('id')->on('categorias');
            $table->foreign('idmarca')->references('id')->on('marcas');
            $table->foreign('idunidad')->references('id')->on('unidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
