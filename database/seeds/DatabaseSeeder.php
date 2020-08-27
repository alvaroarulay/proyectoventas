<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriasTableSeeder::class);
        $this->call(MarcasSeeder::class);
        $this->call(UnidadesSeeder::class);
        $this->call(ArticulosTableSeeder::class);
    }
}
