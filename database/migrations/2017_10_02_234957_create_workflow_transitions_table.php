<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('froms')->nullable();
            $table->string('tos')->nullable();
            $table->string('guard')->nullable();
            $table->string('automatic')->nullable();

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
        Schema::dropIfExists('workflow_transitions');
    }
}
