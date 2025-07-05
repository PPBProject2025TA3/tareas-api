<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    protected $fillable = [
        'titulo', 'cuerpo', 'autor_id', 'asignado_id', 
        'fecha_creacion', 'fecha_expiracion', 'estado'
    ];

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_tarea');
    }
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }
}