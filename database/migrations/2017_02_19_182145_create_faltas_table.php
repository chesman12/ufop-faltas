<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaltasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faltas', function (Blueprint $table) {
            $table->integer('aluno_id')->unsigned();
            $table->integer('turma_id')->unsigned();
            $table->date('data');

            $table->primary(['aluno_id', 'turma_id', 'data']);

            $table->foreign('aluno_id')
                ->references('id')->on('usuarios')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('turma_id')
                ->references('id')->on('turmas')
                ->onDelete('restrict')
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
        Schema::dropIfExists('faltas');
    }
}
