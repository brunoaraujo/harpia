<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegPerfisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_perfis', function (Blueprint $table) {
            $table->increments('prf_id');
            $table->integer('prf_mod_id')->unsigned();
            $table->string('prf_nome');
            $table->string('prf_descricao')->nullable();
            $table->timestamps();

            $table->foreign('prf_mod_id')->references('mod_id')->on('seg_modulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_perfis');
    }
}
