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

    public function getIdProdi()
    {
        $id_prd = Array();
        foreach ($this->getJurusan() as $jurusan) {
            $id_prd = array_merge($id_prd, array_flatten($jurusan->geProdi()->map(function ($prd) {
                return collect($prd->toArray())->only(['id'])->all();
            })));
        }

        return $id_prd;
    }
}