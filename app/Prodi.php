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
     * Mendapatkan data mahasiswa dengan prodi ini
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiMahasiswa()
    {
        return $this->hasMany('App\Mahasiswa', 'prodi_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMahasiswa()
    {
        return $this->hasMany('App\Mahasiswa', 'prodi_id')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiUser()
    {
        return $this->hasMany('App\User', 'prodi_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUser()
    {
        return $this->hasMany('App\User', 'prodi_id')->get();
    }

    /**
     * Mendapatkan relasi jurusan dari prodi ini
     * @return Model|null|static
     */
    public function getRelasiJurusan()
    {
        return $this->belongsTo('App\Jurusan', 'jurusan_id');
    }

    public function getJurusan()
    {
        return $this->belongsTo('App\Jurusan', 'jurusan_id')->first();
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
