<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegPermissoesPerfisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seg_permissoes_perfis', function (Blueprint $table) {
            $table->integer('prp_prf_id')->unsigned();
            $table->integer('prp_prm_id')->unsigned();

            $table->primary(['prp_prf_id', 'prp_prm_id']);

            $table->foreign('prp_prf_id')->references('prf_id')->on('seg_perfis');
            $table->foreign('prp_prm_id')->references('prm_id')->on('seg_permissoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seg_permissoes_perfis');
    }
}
