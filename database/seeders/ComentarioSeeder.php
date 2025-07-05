<?php

namespace Database\Seeders;

use App\Models\Comentario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ComentarioSeeder extends Seeder
{
    public function run()
    {
        $comentarios = [
            [
                'tarea_id' => 1,  
                'usuario_id' => 1,
                'contenido' => 'Hay que revisar los scopes de OAuth',
                'fecha_creacion' => Carbon::now(),
            ],
            [
                'tarea_id' => 1,
                'usuario_id' => 2,
                'contenido' => 'Ya está el endpoint /me en auth-api',
                'fecha_creacion' => Carbon::now()->subHours(2),
            ],
        ];

        foreach ($comentarios as $comentario) {
            Comentario::create($comentario);
        }
    }
}