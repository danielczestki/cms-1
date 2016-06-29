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
            $table->string('status', 20)->nullable()->comment("Video encoding status");
            $table->string('filename', 15)->unique()->comment("Random string, filename and uuid");
            $table->string('extension', 10);
            $table->string('title', 100);
            $table->string('original_name', 255)->nullable();
            $table->string('original_extension', 255)->nullable();
            $table->string('original_filesize', 255)->nullable();
            $table->integer('original_width')->default(0)->unsigned()->comment("Images only");
            $table->integer('original_height')->default(0)->unsigned()->comment("Images only");
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
