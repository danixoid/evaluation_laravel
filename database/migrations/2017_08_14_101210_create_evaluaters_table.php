<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluaters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('evaluation_id');
            $table->unsignedInteger('eval_role_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table
                ->unique(['evaluation_id','eval_role_id','user_id']);
            $table->foreign('evaluation_id')
                ->references('id')->on('evaluations');
            $table->foreign('eval_role_id')
                ->references('id')->on('eval_roles');
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
        Schema::dropIfExists('evaluaters');
    }
}
