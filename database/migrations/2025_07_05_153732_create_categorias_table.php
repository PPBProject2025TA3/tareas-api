<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); 
            $table->timestamps();
        });

        Schema::create('categoria_tarea', function (Blueprint $table) {
            $table->unsignedBigInteger('tarea_id');
            $table->unsignedBigInteger('categoria_id');
            $table->primary(['tarea_id', 'categoria_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
