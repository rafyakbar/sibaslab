<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table){
            $table->string('id')
                ->unique()
                ->primary();
            $table->integer('prodi_id');
            $table->foreign('prodi_id')
                ->references('id')
                ->on('prodi')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->string('nama');
            $table->text('email')->default('');
            $table->char('jk', 1)->nullable();
            $table->float('ipk')->default(0);
            $table->string('ta')->nullable();
            $table->boolean('ajukan')->default(false);
            $table->boolean('konfirmasi')->default(false);
            $table->text('dir')->nullable();
            $table->text('validasi')->nullable();
            $table->text('password')->nullable();
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
