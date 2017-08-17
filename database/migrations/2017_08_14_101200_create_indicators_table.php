<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('competence_id');
            $table->unsignedInteger('eval_level_id')->nullable();
            $table->text('name');
            $table->timestamps();

            $table->unique(['competence_id','eval_level_id']);

            $table->foreign('competence_id')
                ->references('id')
                ->on('competences');

            $table->foreign('eval_level_id')
                ->references('id')
                ->on('eval_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
}
