<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion', 50)->comment('Identificacion');
            $table->string('lugarexpedicion', 50)->nullable()->comment('Lugar de expedición');
            $table->date('fechaexpedicion')->nullable()->comment('Fecha de expedición');
            $table->string('direccion', 150)->nullable()->comment('Dirección');
            $table->string('telefono', 50)->nullable()->comment('Teléfono fijo');
            $table->string('telefonomovil', 10)->comment('Teléfono móvil');
            $table->string('telefonowhatsapp', 50)->nullable();
            $table->string('email', 50)->comment('Email');
            $table->tinyInteger('sendemail')->default(0)->comment('Enviar email');
            $table->date('fechanacimiento')->comment('Fecha nacimiento');
            $table->string('nombre', 50)->comment('Nombre');
            $table->string('segundonombre', 50)->nullable()->comment('Segundo nombre');
            $table->string('apellido', 50)->comment('Apellido');
            $table->string('segundoapellido', 50)->nullable()->comment('Segundo apellido');
            $table->text('foto')->nullable();
            $table->unsignedBigInteger('pais_id')->nullable();
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->unsignedBigInteger('zona_id')->nullable();
            $table->string('barrio', 100)->nullable();
            $table->unsignedBigInteger('tipoidentificacion_id')->nullable();
            $table->unsignedBigInteger('sexo_id');
            $table->unsignedBigInteger('ocupacion_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('pais_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('departamento_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('ciudad_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('zona_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tipoidentificacion_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sexo_id')->references('id')->on('maestras')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ocupacion_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade');
        });

        // Añadir índice único para la columna identificacion
        Schema::table('personas', function (Blueprint $table) {
            $table->unique('identificacion', 'personas_identificacion_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropUnique('personas_identificacion_unique');
        });

        Schema::dropIfExists('personas');
    }
}
