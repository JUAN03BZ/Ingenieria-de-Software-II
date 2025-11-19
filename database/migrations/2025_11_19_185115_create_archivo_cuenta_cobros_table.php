<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoCuentaCobrosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('archivo_cuenta_cobros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuenta_cobro_id');
            $table->string('nombre_original');
            $table->string('ruta');
            $table->timestamps();

            // Relación correcta con la tabla padre
            $table->foreign('cuenta_cobro_id')
                  ->references('id')
                  ->on('cuentas_cobro') // ← este es el nombre correcto
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('archivo_cuenta_cobros');
    }
}
