<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mahasiswa;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    public $timestamps = false;

    protected $fillable = [
        'nama'
    ];

    /**
     * Mendapatkan, memasukkan dan menghapus data prodi
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRelasiProdi()
    {
        return $this->hasMany('App\Prodi', 'jurusan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProdi()
    {
        return $this->hasMany('App\Prodi', 'jurusan_id')->get();
    }

    /**
     * mendapatkan data mahasiswa dari jurusan tertentu
     * @return mixed
     */
    public function getRelasiMahasiswa()
    {
        $id_mhs = Array();
        foreach ($this->getRelasiProdi()->get() as $prodi){
            $id_mhs = array_merge($id_mhs, array_flatten($prodi->getRelasiMahasiswa()->get()->map(function ($mhs){
                return collect($mhs->toArray())->only(['id'])->all();
            })));
        }

        return Mahasiswa::whereIn('mahasiswa.id', $id_mhs);
    }

    /**
     * mengecek apakah jurusan dengan nama tersebut ada atau tidak
     * @param $nama
     * @return bool
     */
    public static function checkByName($name)
    {
        if (Jurusan::where('nama', $name)->count() > 0)
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
}
