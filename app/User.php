<?php

namespace App;

use App\Support\Role;
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
     * mendapatkan relasi prodi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelasiProdi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id');
    }

    /**
     * mendapatkan data prodi
     * @return \Illuminate\Database\Eloquent\Model|null|static
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
     * mendapatkan relasi dengan mahasiswa
     * @return $this
     */
    public function getRelasiMahasiswa()
    {
        return $this->belongsToMany('App\Mahasiswa', 'konfirmasi', 'user_id', 'mahasiswa_id')->withPivot('catatan', 'disetujui')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function getRelasiKasublab()
    {
        return $this->getJurusan()->getRelasiUser()->where('role', Role::KASUBLAB);
    }

    /**
     * Mengecek apakah user adalah admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return ($this->role === Role::ADMIN);
    }
    
    /**
     * Mengecek apakah user adalah kalab
     *
     * @return boolean
     */
    public function isKalab()
    {
        return ($this->role === Role::KALAB);
    }
    
    /**
     * Mengecek apakah user adalah kasublab
     *
     * @return boolean
     */
    public function isKasublab()
    {
        return ($this->role === Role::KASUBLAB);
    }

}
