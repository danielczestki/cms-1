<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsMediaImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_media_images', function (Blueprint $table) {
            $table->integer('cms_medium_id')->unsigned();
            $table->enum('aspect', ['portrait', 'landscape', 'square']);
            $table->string('focal', 20)->default("center")->comment("Anchor point for the cropping");
            $table->integer('original_width')->default(0)->unsigned();
            $table->integer('original_height')->default(0)->unsigned();
            
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
        Schema::drop('cms_media_images');
    }
}
