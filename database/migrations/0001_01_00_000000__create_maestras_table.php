<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaestrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maestras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 255)->nullable();
            $table->string('nombre', 355);
            $table->unsignedBigInteger('padre')->nullable()->comment('Llave foranea del padre');
            $table->unsignedBigInteger('jerarquia')->nullable();
            $table->string('orden', 3)->nullable();
            $table->tinyInteger('visible')->default(1);
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->default(2);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('jerarquia', 'maestras_jerarquia_foreign');
            $table->index('padre', 'maestras_padre_foreign');

            $table->foreign('jerarquia', 'maestras_jerarquia_foreign')
                  ->references('id')
                  ->on('maestras')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('padre', 'maestras_padre_foreign')
                  ->references('id')
                  ->on('maestras')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maestras', function (Blueprint $table) {
            $table->dropForeign('maestras_jerarquia_foreign');
            $table->dropForeign('maestras_padre_foreign');
            $table->dropIndex('maestras_jerarquia_foreign');
            $table->dropIndex('maestras_padre_foreign');
        });

        Schema::dropIfExists('maestras');
    }
}
