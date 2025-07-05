<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index()
    {
        return Categoria::all();
    }

    public function show($id)
    {
        return Categoria::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:categorias'
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        $categoria = Categoria::create(['nombre' => $request->nombre]);
        
        return response()->json($categoria, 201);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        
        $validador = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:categorias,nombre,'.$id
        ]);

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        $categoria->update(['nombre' => $request->nombre]);
        
        return response()->json($categoria);
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->tareas()->detach();
        $categoria->delete();
        
        return response()->json(['eliminado' => true]);
    }
}