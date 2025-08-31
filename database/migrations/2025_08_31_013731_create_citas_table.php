<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // psicólogo
            $table->string('paciente');
            $table->dateTime('fecha_hora');
            $table->string('tipo')->default('psicologica'); // psicologica, legal, social
            $table->string('estado')->default('pendiente'); // pendiente, atendida, cancelada
            $table->string('prioridad')->default('normal'); // alta, normal, baja
            $table->boolean('confirmada')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
