<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuenta_cobro_flujos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_cobro_id');
            $table->unsignedBigInteger('user_id');
            $table->string('rol'); // supervisor, contratacion, tesoreria, ordenador_gasto, contratista
            $table->string('accion'); // enviado, aprobado, rechazado, observado
            $table->text('comentario')->nullable();
            $table->timestamps();
            $table->foreign('cuenta_cobro_id')->references('id')->on('cuentas_cobro')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuenta_cobro_flujos');
    }
};
