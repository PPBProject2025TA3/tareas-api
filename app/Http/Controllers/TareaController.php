<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TareaController extends Controller
{
    public function index()
    {
        return Tarea::with(['categorias', 'comentarios'])->get();
    }

    public function store(Request $request)
    {
        $response = Http::withToken($request->bearerToken())
                       ->get('http://auth-api/api/me');

        if (!$response->ok()) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $tarea = Tarea::create($request->all());
        
        if ($request->has('categorias')) {
            $tarea->categorias()->sync($request->categorias);
        }

        return response()->json($tarea, 201);
    }

    public function show(Tarea $tarea)
    {
        return $tarea->load(['categorias', 'comentarios']);
    }
}