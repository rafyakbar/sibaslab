<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KonfirmasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konfirmasi', function (Blueprint $table){
            $table->string('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('mahasiswa_id');
            $table->foreign('mahasiswa_id')
                ->references('id')
                ->on('mahasiswa')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->text('catatan')->nullable();
            $table->boolean('disetujui');
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
        //
    }
}
