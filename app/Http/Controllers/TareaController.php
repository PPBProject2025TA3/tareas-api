<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\AsignacionTarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TareaController extends Controller
{
    
    protected function validarDatos(Request $request, array $reglasAdicionales = [])
    {
        $reglas = array_merge([
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'autor_id' => 'required|integer|exists:users,id',
            'fecha_inicio' => 'nullable|date_format:Y-m-d H:i:s',
            'fecha_vencimiento' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:fecha_inicio',
            'estado' => 'nullable|string|in:pendiente,en_progreso,completada',
            'categorias' => 'nullable|array',
            'categorias.*' => 'integer|exists:categorias,id',
            'asignados' => 'nullable|array',
            'asignados.*' => 'integer|exists:users,id'
        ], $reglasAdicionales);

        return Validator::make($request->all(), $reglas);
    }

    public function index(Request $request)
    {
        $cacheKey = $this->generarClaveCache($request);
        
        return Cache::remember($cacheKey, 180, function() use ($request) {
            $query = Tarea::with(['categorias', 'comentarios', 'asignados']);
            
            $this->aplicarFiltros($query, $request);
            
            return $query->get();
        });
    }

    public function show($id)
    {
        return Cache::remember('tarea_'.$id, 180, function() use ($id) {
            return Tarea::with(['categorias', 'comentarios', 'asignados'])
                        ->findOrFail($id);
        });
    }

    public function store(Request $request)
    {
        $validador = $this->validarDatos($request);
        
        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        return DB::transaction(function() use ($request) {
            $tarea = Tarea::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'autor_id' => $request->autor_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'estado' => $request->estado ?? 'pendiente'
            ]);

            $this->sincronizarRelaciones($tarea, $request);
            $this->enviarAHistorial($tarea);
            
            Cache::tags('tareas')->flush();
            
            return response()->json($tarea->load(['categorias', 'asignados']), 201);
        });
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);
        $validador = $this->validarDatos($request);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        return DB::transaction(function() use ($request, $tarea) {
            $tarea->update([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'estado' => $request->estado
            ]);

            $this->sincronizarRelaciones($tarea, $request);
            
            Cache::tags('tareas')->flush();
            Cache::forget('tarea_'.$tarea->id);
            
            return response()->json($tarea->load(['categorias', 'asignados']));
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function() use ($id) {
            $tarea = Tarea::findOrFail($id);
            
            $tarea->categorias()->detach();
            $tarea->comentarios()->delete();
            $tarea->asignados()->detach(); 
            $tarea->delete();
            
            Cache::tags('tareas')->flush();
            
            return response()->json(['eliminado' => true]);
        });
    }

    private function generarClaveCache(Request $request)
    {
        $filtros = collect($request->query())
            ->sortKeys()
            ->map(fn($v, $k) => "{$k}_{$v}")
            ->implode('_');
            
        return $filtros ? 'tareas_'.$filtros : 'tareas';
    }

    private function aplicarFiltros($query, Request $request)
    {
        if ($request->has('autor_id')) {
            $query->where('autor_id', $request->autor_id);
        }

        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%'.$request->titulo.'%');
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('asignado_id')) {
            $query->whereHas('asignados', fn($q) => 
                $q->where('users.id', $request->asignado_id)); 
        }
    }

    private function sincronizarRelaciones(Tarea $tarea, Request $request)
    {
        if ($request->has('categorias')) {
            $tarea->categorias()->sync($request->categorias);
        }

        if ($request->has('asignados')) {
            $tarea->asignados()->sync(
                collect($request->asignados)->mapWithKeys(fn($id) => [
                    $id => ['fecha_asignacion' => now()]
                ])
            );
        }
    }

    private function enviarAHistorial(Tarea $tarea)
    {
        Http::withHeaders([
            'Accept' => 'application/json'
        ])->post(config('services.historial.url'), [
            'tarea_id' => $tarea->id,
            'accion' => 'creada',
            'detalles' => $tarea->only(['titulo', 'estado', 'autor_id'])
        ]);
    }
}