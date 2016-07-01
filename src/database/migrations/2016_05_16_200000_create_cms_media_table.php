<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_media', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['image', 'video', 'document', 'embed']);
            $table->string('disk', 15);
            $table->string('visibility', 15)->nullable();
            $table->string('cache_buster', 15)->nullable();
            $table->string('title', 100);
            $table->boolean('uploaded')->default(0)->comment("Did the file upload successfully");
            $table->string('filename', 20)->nullable();
            $table->string('extension', 6)->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('original_extension', 15)->nullable();
            $table->string('original_mime', 15)->nullable();
            $table->bigInteger('original_filesize')->default(0);
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
        Schema::drop('cms_media');
    }
}
