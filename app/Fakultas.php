<?php

namespace App;

use App\Support\Role;
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

    /**
     * mendapatkan id user
     * @return array
     */
    public function getIdUser()
    {
        $id_usr = Array();
        foreach ($this->getJurusan() as $jurusan){
            $id_usr = array_merge($id_usr, $jurusan->getIdUser());
        }

        return $id_usr;
    }

    /**
     * mendapatkan relasi user
     * @return mixed
     */
    public function getRelasiUser()
    {
        return User::whereIn('id', $this->getIdUser());
    }

    /**
     * mendapatkan kalab dan kasublab
     * @return mixed
     */
    public function getKalabKasublab()
    {
        return $this->getRelasiUser()->whereNotIn('role', [Role::ROOT, Role::ADMIN])->get();
    }

    /**
     * mendapatkan id semua mahasiswa dari fakultas tertentu
     * @return array
     */
    public function getIdMahasiswa()
    {
        $id_mhs = Array();
        foreach ($this->getJurusan() as $jurusan){
            $id_mhs = array_merge($id_mhs, $jurusan->getIdMahasiswa());
        }

        return $id_mhs;
    }

    /**
     * mendapatkan relasi mahasiswa dari fakultas tertentu
     * @return mixed
     */
    public function getRelasiMahasiswa()
    {
        return Mahasiswa::whereIn('id', $this->getIdMahasiswa());
    }

    /**
     * mendapatkan data mahasiswa dari fakultas tertentu
     * @return mixed
     */
    public function getMahasiswa()
    {
        return $this->getRelasiMahasiswa()->get();
    }
}