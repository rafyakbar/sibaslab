<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mahasiswa;
use App\Support\Role;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    public $timestamps = false;

    protected $fillable = [
        'nama', 'fakultas_id'
    ];

    /**
     * Mendapatkan relasi prodi
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiProdi()
    {
        return $this->hasMany('App\Prodi', 'jurusan_id');
    }

    /**
     * mendapatkan data semua prodi dari jurusan tertentu
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProdi()
    {
        return $this->getRelasiProdi()->get();
    }

    /**
     * mendapatkan relasi fakultas
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelasiFakultas()
    {
        return $this->belongsTo('App\Fakultas', 'fakultas_id');
    }

    /**
     * mendapatkan data fakultas
     * @return Model|null|static
     */
    public function getFakultas()
    {
        return $this->getRelasiFakultas()->first();
    }

    /**
     * mendapatkan id mahasiswa dari jurusan ini
     * @return array
     */
    public function getIdMahasiswa()
    {
        $id_mhs = Array();
        foreach ($this->getProdi() as $prodi) {
            $id_mhs = array_merge($id_mhs, array_flatten($prodi->getMahasiswa()->map(function ($mhs) {
                return collect($mhs->toArray())->only(['id'])->all();
            })));
        }

        return $id_mhs;
    }

    /**
     * mendapatkan relasi mahasiswa dari jurusan tertentu
     * @return mixed
     */
    public function getRelasiMahasiswa()
    {
        return Mahasiswa::whereIn('id', $this->getIdMahasiswa());
    }

    /**
     * mendapatkan data mahasiswa
     * @return mixed
     */
    public function getMahasiswa()
    {
        return $this->getRelasiMahasiswa()->get();
    }

    /**
     * mendapatkan id user dari jurusan tertentu
     * @return array
     */
    public function getIdUser()
    {
        $id_usr = Array();
        foreach ($this->getProdi() as $prodi) {
            $id_usr = array_merge($id_usr, array_flatten($prodi->getUser()->map(function ($usr) {
                return collect($usr->toArray())->only(['id'])->all();
            })));
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
     * mengecek apakah jurusan dengan nama tersebut ada atau tidak
     * @param $nama
     * @return bool
     */
    public static function checkByName($name, $fakultas_id = null)
    {
        if (is_null($fakultas_id)){
            if (Jurusan::where('nama', $name)->count() > 0)
                return true;
            return false;
        }
        if (Jurusan::where('nama', $name)->where('fakultas_id', $fakultas_id)->count() > 0)
            return true;
        return false;
    }

    /**
     * mendapatkan jurusan dengan nama
     * @param $name
     * @return mixed
     */
    public static function findByName($name)
    {
        return Jurusan::where('nama', $name)->first();
    }

    /**
     * Mendapatkan daftar kasublab pada jurusan tertentu
     *
     * @param boolean $queryReturn
     * @return mixed
     */
    public function getDaftarKasublab($queryReturn = false)
    {
        $daftarKasublab = $this->getRelasiUser()->where('role', Role::KASUBLAB);

        if($queryReturn)
            return $daftarKasublab;

        return $daftarKasublab->get();
    } 

}
