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
     * Mendapatkan relasi ke tabel prodi
     * gunanya untuk bisa menggunakan whereHas
     *
     * @return BelongsTo
     */
    public function getRelasiProdi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id');
    }

    /**
     * @return Model|null|static
     */
    public function getProdi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id')->first();
    }

    /**
     * @return mixed
     */
    public function getRelasiJurusan()
    {
        return $this->getProdi()->getRelasiJurusan();
    }

    public function getJurusan()
    {
        return $this->getProdi()->getJurusan();
    }

    /**
     * @return $this
     */
    public function getRelasiKonfirmasiUser()
    {
        return $this->belongsToMany('App\User', 'konfirmasi', 'mahasiswa_id', 'user_id')->withPivot('catatan')->withTimestamps();
    }
    
}
