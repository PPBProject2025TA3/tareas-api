<?php

namespace Database\Seeders;

use App\Models\Tarea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TareaSeeder extends Seeder
{
    public function run()
    {
        $tareas = [
            [
                'titulo' => 'Implementar autenticación',
                'cuerpo' => 'Integrar OAuth2 con auth-api',
                'autor_id' => 1,  
                'estado' => 'pendiente',
                'fecha_creacion' => Carbon::now(),
                'fecha_expiracion' => Carbon::now()->addDays(7),
            ],
            [
                'titulo' => 'Crear migraciones',
                'cuerpo' => 'Definir esquemas para tareas y comentarios',
                'autor_id' => 1,
                'estado' => 'completada',
                'fecha_creacion' => Carbon::now()->subDays(3),
                'fecha_expiracion' => null,
            ],
        ];

        foreach ($tareas as $tarea) {
            $tareaCreada = Tarea::create($tarea);
            
            $tareaCreada->categorias()->attach([1, 2]);
        }
    }
}