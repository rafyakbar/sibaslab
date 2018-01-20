<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nama', 'password', 'role', 'jurusan_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelasiProdi()
    {
        return $this->belongsTo('App\Prodi', 'jurusan_id');
    }

    /**
     * @return mixed
     */
    public function getRelasiJurusan()
    {
        return $this->getRelasiProdi()->getRelasiJurusan();
    }

    /**
     * @return $this
     */
    public function getRelasiKonfirmasiMahasiswa()
    {
        return $this->belongsToMany('App\Mahasiswa', 'konfirmasi', 'user_id', 'mahasiswa_id')->withPivot('catatan');
    }
}
