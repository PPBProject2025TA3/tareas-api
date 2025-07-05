<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    protected $fillable = ['contenido', 'usuario_id', 'fecha_creacion'];

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }
}