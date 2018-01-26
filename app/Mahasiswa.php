<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{

    use Notifiable;

    protected $table = 'mahasiswa';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'prodi_id', 'nama', 'password', 'konfirmasi', 'dir', 'validasi', 'created_at', 'updated_at'
    ];

    /**
     * Mendapatkan relasi ke prodi
     * gunanya untuk bisa menggunakan whereHas
     *
     * @return BelongsTo
     */
    public function getRelasiProdi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id');
    }

    /**
     * mendapatkan data prodi
     * @return Model|null|static
     */
    public function getProdi()
    {
        return $this->getRelasiProdi()->first();
    }

    /**
     * mendapatkan relasi jurusan
     * @return mixed
     */
    public function getRelasiJurusan()
    {
        return $this->getProdi()->getRelasiJurusan();
    }

    /**
     * mendapatkan data jurusan
     * @return mixed
     */
    public function getJurusan()
    {
        return $this->getProdi()->getJurusan();
    }

    /**
     * mendapatkan relasi fakultas
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
     * mendapatkan relasi dengan dosen
     * @return $this
     */
    public function getRelasiUser()
    {
        return $this->belongsToMany('App\User', 'konfirmasi', 'mahasiswa_id', 'user_id')->withPivot('catatan', 'disetujui')->withTimestamps();
    }

    /**
     * mendapatkan kasublab atau kalab yang menyetujui
     * @return mixed
     */
    public function getUserYangMenyetujui()
    {
        return $this->getRelasiUser()->where('disetujui', true)->get();
    }

    /**
     * mendapatkan kasub
     * @return mixed
     */
    public function getUserYangMenolak()
    {
        return $this->getRelasiUser()->where('disetujui', false)->get();
    }
}
