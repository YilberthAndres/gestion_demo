<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('em_sedes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre', 255);
            $table->string('web', 255);
            $table->string('direccion', 80)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('telefono', 80)->nullable();
            $table->string('prestador', 80)->nullable();
            $table->string('nom_representante', 80)->nullable();
            $table->string('email_representante', 80)->nullable();
            $table->string('tel_representante', 80)->nullable();
            $table->bigInteger('representante_id')->nullable();
            $table->longText('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('deleted_by')->nullable();
            $table->integer('created_by_id');
            $table->integer('estado_id')->nullable();
            $table->integer('updated_by_id')->nullable();

            // Indexes
            $table->index('created_by_id', 'em_sedes_created_by_id_a0f2152d_fk_users_id');
            $table->index('estado_id', 'em_sedes_estado_id_19c336d1_fk_maestras_id');
            $table->index('updated_by_id', 'em_sedes_updated_by_id_ae5e2506_fk_users_id');

            // Foreign keys
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
            $table->foreign('estado_id')->references('id')->on('maestras')->onDelete('no action')->onUpdate('no action');
            $table->foreign('updated_by_id')->references('id')->on('users')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('em_sedes');
    }
}
