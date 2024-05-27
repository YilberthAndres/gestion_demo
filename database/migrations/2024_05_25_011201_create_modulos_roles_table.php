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
        Schema::create('modulos_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos_roles');
    }
};
