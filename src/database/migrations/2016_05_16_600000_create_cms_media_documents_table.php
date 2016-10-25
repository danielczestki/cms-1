<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsMediaDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_media_documents', function (Blueprint $table) {
            $table->integer('cms_medium_id')->unsigned();
            // nothing yet, but we need this table and will want it for
            // future columns specific to document... cos there will be!
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
        Schema::drop('cms_media_documents');
    }
}
