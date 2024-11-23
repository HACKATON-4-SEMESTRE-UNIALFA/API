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
        Schema::create('historico_reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reserva')->constrained('reservas')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('id_ambiente')->constrained('ambientes')->onDelete('cascade');
            $table->string('id_alteracao');
            $table->string('horario');
            $table->string('data');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_reservas');
    }
};
