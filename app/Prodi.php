<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'jurusan_id'
    ];

    /**
     * Mendapatkan relasi mahasiswa dengan prodi tertentu
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiMahasiswa()
    {
        return $this->hasMany('App\Mahasiswa', 'prodi_id');
    }

    /**
     * mendapatkan data mahasiswa prodi tertentu
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMahasiswa()
    {
        return $this->getRelasiMahasiswa()->get();
    }

    /**
     * mendapatkan relasi user dengan prodi tertentu
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiUser()
    {
        return $this->hasMany('App\User', 'prodi_id');
    }

    /**
     * mendapatkan data user dengan prodi tertentu
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUser()
    {
        return $this->getRelasiUser()->get();
    }

    /**
     * Mendapatkan relasi jurusan dengan prodi tertentu
     * @return Model|null|static
     */
    public function getRelasiJurusan()
    {
        return $this->belongsTo('App\Jurusan', 'jurusan_id');
    }

    /**
     * mendapatkan data jurusan dari prodi tertentu
     * @return mixed
     */
    public function getJurusan()
    {
        return $this->getRelasiJurusan()->first();
    }

    /**
     * mendapatkan relasi fakultas dengan prodi tertentu
     * @return mixed
     */
    public function getRelasiFakultas()
    {
        return $this->getJurusan()->getRelasiFakultas();
    }

    /**
     * mendapatkan data fakultas
     * @return mixed
     */
    public function getFakultas()
    {
        return $this->getRelasiFakultas()->first();
    }

    /**
     * Mendapatkan data prodi dari nama
     * @param $nama
     * @return mixed
     */
    public static function findByName($nama)
    {
        return Prodi::where('nama', $nama)->first();
    }
}
