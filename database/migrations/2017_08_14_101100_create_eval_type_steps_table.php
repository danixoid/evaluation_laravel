<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvalTypeStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eval_type_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eval_type_id');
            $table->unsignedInteger('eval_step_id');


            $table->foreign('eval_type_id')
                ->references('id')
                ->on('eval_types');
            $table->foreign('eval_step_id')
                ->references('id')
                ->on('eval_steps');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eval_type_steps');
    }
}
