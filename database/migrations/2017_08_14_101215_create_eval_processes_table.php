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
            $table->unsignedInteger('eval_user_id');
            $table->unsignedInteger('competence_id');
            $table->timestamps();

            $table->foreign('eval_user_id')
                ->references('id')->on('eval_users');
            $table->foreign('competence_id')
                ->references('id')->on('competences');
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
