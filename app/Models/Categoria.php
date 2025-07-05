<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    protected $fillable = ['nombre'];


    public function tareas(): BelongsToMany
    {
        return $this->belongsToMany(Tarea::class, 'categoria_tarea');
    }
}