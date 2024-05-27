<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191)->nullable();
            $table->string('path', 191)->nullable();
            $table->string('icon', 191)->nullable();
            $table->string('parent_id', 191)->nullable();
            $table->integer('order')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->integer('created_by_id');
            $table->integer('updated_by_id')->nullable();

            $table->index('created_by_id', 'modulos_created_by_id_682736db_fk_auth_user_id');
            $table->index('updated_by_id', 'modulos_updated_by_id_379626ca_fk_auth_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modulos');
    }
}
