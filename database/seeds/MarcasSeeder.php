<?php

use Illuminate\Database\Seeder;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas=array('Samsung','Sony','Akita','Philips','Genius','GoodTecnology');
        $iniciales=array('SA','SO','AK','PH','GE','GT');
        for($i=0;$i<6;$i++){
            DB::table('marcas')->insert([
                'nombre' =>$marcas[$i],
                'inicial'=>$iniciales[$i],
                'descripcion'=>'esta marca comprende '.$marcas[$i],
                'created_at'=>now(),
                'updated_at'=>now(),
               ]);
            }
    }
}
