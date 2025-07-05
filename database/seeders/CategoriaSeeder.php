<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['nombre' => 'Urgente'],
            ['nombre' => 'Backend'],
            ['nombre' => 'Frontend'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}