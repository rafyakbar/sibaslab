<?php

namespace App;

use App\Support\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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
     * mendapatkan data user
     * @return mixed
     */
    public function getUser()
    {
        return $this->getRelasiUser()->get();
    }

    /**
     * mendapatkan id user
     * @return array
     */
    public function getIdUser()
    {
        return array_flatten($this->getUser()->map(function ($usr) {
            return collect($usr->toArray())->only(['id'])->all();
        }));
    }

    /**
     * mendapatkan kasublab atau kalab yang menyetujui
     * @return mixed
     */
    public function getKalabKasublabYangMenyetujui()
    {
        return $this->getRelasiUser()
            ->where('disetujui', true)
            ->orderBy('role')
            ->get();
    }

    /**
     * mendapatkan kasublab atau kalab yang menolak
     * @return mixed
     */
    public function getKalabKasublabYangMenolak()
    {
        return $this->getRelasiUser()
            ->where('disetujui', false)
            ->orderBy('role')
            ->get();
    }

    /**
     * mendapatkan data kalab n kasublab yang belum menyetujui
     * @return mixed
     */
    public function getKalabKasublabYangBelumMenyetujui()
    {
        return $this->getProdi()
            ->getJurusan()
            ->getRelasiUser()
            ->whereNotIn('id', $this->getIdUser())
            ->whereIn('role', [Role::KALAB, Role::KASUBLAB])
            ->get();
    }

    /**
     * Mendapatkan daftar mahasiswa sesuai status yang diinginkan
     * daftar status :
     * 0 -> mahasiswa dengan surat belum ditanggapi atau surat belum disetujui
     * 1 -> mahasiswa dengan surat telah disetujui
     * !1 / !0 -> mahasiswa dengan surat telah ditolak
     *
     * @param mixed $name
     * @param Jurusan $jurusan
     * @param integer $status
     * @return void
     */
    public static function getMahasiswaByStatus($user, $status = 0, $additionalCounter = true) 
    {
        // jika status 0, maka surat belum ditanggapi
        if($status == 0) {
            $daftarMahasiswa = $user->getJurusan()->getRelasiMahasiswa()->whereNotIn('id', $user->getRelasiMahasiswa()->get()->pluck('id')->toArray());
        }
        // jika status 1, maka surat telah disetujui
        else if($status == 1) {
            $daftarMahasiswa = $user->getRelasiMahasiswa()->wherePivot('disetujui', true);
        }
        // jika status 2 atau lebih, maka surat belum disetujui
        else {          
            $daftarMahasiswa = $user->getRelasiMahasiswa()->wherePivot('disetujui', false);
        }

        if(!$additionalCounter)
            return $daftarMahasiswa->get();

        return $daftarMahasiswa->get()->each(function ($mahasiswa) {
            $mahasiswa['belum_menanggapi'] = $mahasiswa->getKalabKasublabYangBelumMenyetujui()->count();
            $mahasiswa['menyetujui'] = $mahasiswa->getKalabKasublabYangMenyetujui()->count();
            $mahasiswa['menolak'] = $mahasiswa->getKalabKasublabYangMenolak()->count();
        });
    }
}
