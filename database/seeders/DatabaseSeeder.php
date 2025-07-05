<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()

    {
        $this->call([
            CategoriaSeeder::class,
            TareaSeeder::class,
            ComentarioSeeder::class,
        ]);
    }
}