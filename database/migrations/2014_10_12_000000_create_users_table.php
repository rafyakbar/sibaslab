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
            $table->integer('prodi_id')
                ->unsigned();
            $table->foreign('prodi_id')
                ->references('id')
                ->on('prodi')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->string('nama');
            $table->string('password');
            $table->string('role');
            $table->boolean('tambah_kasublab')
                ->default(false);
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
