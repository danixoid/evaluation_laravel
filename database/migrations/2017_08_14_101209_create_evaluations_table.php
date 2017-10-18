<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->unsignedInteger('func_id')->nullable();
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('eval_type_id');
            $table->timestamp('started_at')->nullable(); //время завершения
            $table->timestamp('finished_at')->nullable(); //время завершения
            $table->timestamps();

            $table->foreign('org_id')
                ->references('id')->on('orgs');
            $table->foreign('func_id')
                ->references('id')->on('funcs');
            $table->foreign('position_id')
                ->references('id')->on('positions');
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('eval_type_id')
                ->references('id')->on('eval_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}
