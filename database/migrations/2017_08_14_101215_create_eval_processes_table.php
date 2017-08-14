<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvalProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eval_processes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('evaluation_id');
            $table->unsignedInteger('eval_step_id');
            $table->unsignedInteger('eval_level_id');
            $table->unsignedInteger('competence_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('evaluation_id')
                ->references('id')->on('evaluations');
            $table->foreign('eval_step')
                ->references('id')->on('eval_steps');
            $table->foreign('eval_level_id')
                ->references('id')->on('eval_levels');
            $table->foreign('competence_id')
                ->references('id')->on('competences');
            $table->foreign('user_id')
                ->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eval_processes');
    }
}
