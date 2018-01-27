<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';

    public $timestamps = false;

    protected $fillable = [
        'nama'
    ];

    /**
     * Mendapatkan relasi jurusan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiJurusan()
    {
        return $this->hasMany('App\Jurusan', 'fakultas_id');
    }

    /**
     * mendapatkan data semua prodi dari fakultas tertentu
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getJurusan()
    {
        return $this->getRelasiJurusan()->get();
    }
}