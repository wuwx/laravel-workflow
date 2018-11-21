<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('subject');
            $table->string('workflow_name');
            $table->string('transition_name');
            $table->string('transition_froms')->nullable();
            $table->string('transition_tos')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('workflow_histories');
    }
}
