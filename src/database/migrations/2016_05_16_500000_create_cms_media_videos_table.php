<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsMediaVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_media_videos', function (Blueprint $table) {
            $table->integer('cms_medium_id')->unsigned();
            $table->string('job_status', 30)->default("Submitted");
            $table->string('job_id', 80)->nullable();
            $table->string('arn')->nullable();
            $table->string('pipeline_id', 80)->nullable();
            
            $table->primary('cms_medium_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cms_media_videos');
    }
}
