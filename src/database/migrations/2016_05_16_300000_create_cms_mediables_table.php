<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsMediablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_mediables', function (Blueprint $table) {
            $table->integer('media_id')->unsigned();
            $table->integer('mediable_id')->unsigned();
            $table->string('mediable_type', 80);
            $table->string('mediable_category', 30)->default("default");
            $table->integer('position')->unsigned()->default(1);
            
            $table->primary(['media_id', 'mediable_id', 'mediable_type', 'mediable_category'], "cms_mediables_pk");
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cms_mediables');
    }
}
