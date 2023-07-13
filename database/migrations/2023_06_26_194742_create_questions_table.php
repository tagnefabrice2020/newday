<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->bigInteger('topic_id')->unsigned();
            $table->bigInteger('created_by')->unsigned();
            $table->string('question');
            $table->string('tags')->nullable();
            $table->string('correct_feedback')->nullable();
            $table->string('incorrect_feedback')->nullable();
            
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('questions');
    }
}
