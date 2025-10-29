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
        Schema::create('cuentas_cobro', function (Blueprint $table) {
            $table->id();

            // Datos del cobrador
            $table->string('nombre_cobrador', 255);
            $table->string('documento_cobrador', 20)->unique();
            $table->string('direccion_cobrador', 500);
            $table->string('telefono_cobrador', 20);
            $table->string('email_cobrador')->unique();

            // Datos del cliente
            $table->string('nombre_cliente', 255);
            $table->string('documento_cliente', 20)->unique();

            // Información de la cuenta
            $table->decimal('monto', 15, 2);
            $table->text('descripcion')->nullable();
            $table->date('fecha_emision')->nullable();

            // Estado y relación con el usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('estado')->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_cobro');
    }
};
