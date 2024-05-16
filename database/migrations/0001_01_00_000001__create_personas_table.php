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
            $table->string('identificacion', 50)->comment('Identificacion')->nullable();
            $table->string('lugarexpedicion', 50)->nullable()->comment('Lugar de expedición')->nullable();
            $table->date('fechaexpedicion')->nullable()->comment('Fecha de expedición')->nullable();
            $table->string('direccion', 150)->nullable()->comment('Dirección')->nullable();
            $table->string('telefono', 50)->nullable()->comment('Teléfono fijo')->nullable();
            $table->string('telefonomovil', 10)->comment('Teléfono móvil')->nullable();
            $table->string('telefonowhatsapp', 50)->nullable();
            $table->string('email', 50)->comment('Email');
            $table->tinyInteger('sendemail')->default(0)->comment('Enviar email')->nullable();
            $table->date('fechanacimiento')->comment('Fecha nacimiento')->nullable();
            $table->string('nombre', 50)->comment('Nombre');
            $table->string('segundonombre', 50)->nullable()->comment('Segundo nombre')->nullable();
            $table->string('apellido', 50)->comment('Apellido');
            $table->string('segundoapellido', 50)->nullable()->comment('Segundo apellido')->nullable();
            $table->text('foto')->nullable();
            $table->unsignedBigInteger('pais_id')->nullable();
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->unsignedBigInteger('zona_id')->nullable();
            $table->string('barrio', 100)->nullable();
            $table->unsignedBigInteger('tipoidentificacion_id')->nullable();
            $table->unsignedBigInteger('sexo_id')->nullable();
            $table->unsignedBigInteger('ocupacion_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->default(1);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('pais_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('departamento_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('ciudad_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('zona_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('tipoidentificacion_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
            $table->foreign('sexo_id')->references('id')->on('maestras')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreign('ocupacion_id')->references('id')->on('maestras')->onDelete('set null')->onUpdate('cascade')->nullable();
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
