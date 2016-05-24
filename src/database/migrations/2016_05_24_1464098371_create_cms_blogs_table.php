<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title", 150);
            $table->string("summary", 2000);
            $table->integer("age")->unsigned()->nullable();
            $table->string("long_summary", 4000);
            $table->text("body");
            $table->string("category", 255)->nullable();
            $table->boolean("is_live")->nullable();
            $table->boolean("another_checkbox")->nullable();
            $table->boolean("my_radio_yes_no")->nullable();
            $table->string("my_radio", 255)->nullable();
            $table->string("author_email", 255)->nullable();
            $table->dateTime("published_at")->nullable();
            $table->date("date_at")->nullable();
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
        Schema::drop('cms_blogs');
    }
}