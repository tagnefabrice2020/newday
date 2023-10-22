<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BundleTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_topic', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("bundle_id")->unsigned();
            $table->bigInteger("topic_id")->unsigned();
            $table->boolean("status")->default(true);
            $table->foreign('bundle_id')->references('id')->on('bundles');
            $table->foreign('topic_id')->references('id')->on('topics');

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
        Schema::dropIfExists('bundle_topic');
    }
}
