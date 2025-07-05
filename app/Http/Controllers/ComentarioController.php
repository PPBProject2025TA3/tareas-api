<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    public function index()
    {
        return Comentario::all();
    }

    public function show($id)
    {
        return Comentario::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'contenido' => 'required|string',
            'autor_id' => 'required|integer|exists:users,id',
            'tarea_id' => 'required|integer|exists:tareas,id'
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        $comentario = Comentario::create($request->only(['contenido', 'autor_id', 'tarea_id']));
        Cache::forget('tarea_'.$request->tarea_id);
        
        return response()->json($comentario, 201);
    }

    public function update(Request $request, $id)
    {
        $comentario = Comentario::findOrFail($id);
        
        $validador = Validator::make($request->all(), [
            'contenido' => 'required|string'
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        $comentario->update(['contenido' => $request->contenido]);
        Cache::forget('tarea_'.$comentario->tarea_id);
        
        return response()->json($comentario);
    }

    public function destroy($id)
    {
        $comentario = Comentario::findOrFail($id);
        $tareaId = $comentario->tarea_id;
        $comentario->delete();
        Cache::forget('tarea_'.$tareaId);
        
        return response()->json(['eliminado' => true]);
    }
}