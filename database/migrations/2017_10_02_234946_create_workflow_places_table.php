<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            $table->integer('workflow_id')->unsigned()->nullable();
            $table->integer('process_id')->unsigned()->nullable();
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
        Schema::dropIfExists('workflow_places');
    }
}
