<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 20);
            $table->string('surname', 20);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('access_level', 20)->default("Standard");
            $table->string('permissions', 4000)->nullable()->comment("Comma list of accessbile modules");
            $table->rememberToken();
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
        Schema::drop('cms_users');
    }
}
