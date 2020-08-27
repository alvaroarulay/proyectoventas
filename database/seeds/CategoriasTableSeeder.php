<?php

use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias=array('Televisores','Equipos de Computación','Camaras','Circuitos Integrados','Cables','Adaptadores');
        $iniciales=array('T','EC','C','CI','CA','A');
        for($i=0;$i<6;$i++){
            DB::table('categorias')->insert([
                'nombre' =>$categorias[$i],
                'inicial'=>$iniciales[$i],
                'descripcion'=>'esta categoria comprende '.$categorias[$i],
                'created_at'=>now(),
                'updated_at'=>now(),
               ]);
            }
    }
}
