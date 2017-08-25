<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('eval_type_id');
            $table->unsignedInteger('eval_role_id');
            $table->unsignedInteger('min');
            $table->unsignedInteger('max');
            $table->timestamps();

            $table
                ->unique(['eval_type_id','eval_role_id']);
            $table->foreign('eval_type_id')
                ->references('id')->on('eval_types');
            $table->foreign('eval_role_id')
                ->references('id')->on('eval_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_roles');
    }
}
