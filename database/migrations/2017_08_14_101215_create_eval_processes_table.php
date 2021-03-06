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
            $table->unsignedInteger('evaluater_id');
            $table->unsignedInteger('indicator_id');
            $table->unsignedInteger('eval_level_id')->nullable();
            $table->timestamps();

            $table
                ->unique(['evaluater_id','indicator_id']);
            $table->foreign('evaluater_id')
                ->references('id')->on('evaluaters');
            $table->foreign('indicator_id')
                ->references('id')->on('indicators');
            $table->foreign('eval_level_id')
                ->references('id')->on('eval_levels');
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
