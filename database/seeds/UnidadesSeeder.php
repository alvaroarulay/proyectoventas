<?php

use Illuminate\Database\Seeder;

class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unidades=array('caja','metros','unidad');
        for($i=0;$i<3;$i++){
            DB::table('unidades')->insert([
                'nombre' =>$unidades[$i],
                'descripcion'=>'esta unidad comprende '.$unidades[$i],
                'created_at'=>now(),
                'updated_at'=>now(),
               ]);
            }
    }
}
