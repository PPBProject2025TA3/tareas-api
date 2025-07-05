<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    protected $fillable = [
        'titulo', 
        'cuerpo', 
        'autor_id', 
        'asignado_id', 
        'fecha_creacion', 
        'fecha_expiracion', 
        'estado'
    ];

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_tarea');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    public function asignados(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'asignacion_tareas', 'tarea_id', 'user_id')
                   ->withPivot('fecha_asignacion')
                   ->withTimestamps();
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}