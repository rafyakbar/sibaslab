<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')
                ->unique()
                ->primary();
            $table->string('nama');
            $table->string('password');
            $table->string('role');
            $table->integer('jurusan_id')
                ->unsigned();
            $table->foreign('jurusan_id')
                ->references('id')
                ->on('jurusan')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
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
        Schema::dropIfExists('users');
    }
}
