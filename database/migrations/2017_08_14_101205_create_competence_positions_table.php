<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetencePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('competence_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('org_id');
            $table->unsignedInteger('func_id')->nullable();
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('competence_id');
            $table->timestamps();

            $table->foreign('org_id')
                ->references('id')->on('orgs');
            $table->foreign('func_id')
                ->references('id')->on('funcs');
            $table->foreign('position_id')
                ->references('id')->on('positions');
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
        Schema::dropIfExists('competence_positions');
    }
}
