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
        'id', 'prodi_id', 'nama', 'password', 'konfirmasi', 'dir', 'validasi', 'created_at', 'updated_at', 'ipk', 'ta', 'email', 'ajukan', 'jk', 'mengajukan_pada'
    ];

    public function getKalab()
    {
        return $this->getRelasiUser()
            ->whereIn('role', [Role::KALAB])
            ->orderBy('role')
            ->get();
    }

    public function getKasublab()
    {
        return $this->getRelasiUser()
            ->whereIn('role', [Role::KASUBLAB])
            ->orderBy('role')
            ->get();
    }

    /**
     * Mendapatkan relasi ke prodi
     * gunanya untuk bisa menggunakan whereHas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
            ->whereIn('role', [Role::KALAB, Role::KASUBLAB])
            ->wherePivot('disetujui', true)
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
            ->where('created_at', '<=', $this->mengajukan_pada)
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
    public static function getMahasiswaByStatus($user, $status = 0, $additionalCounter = true, $keyword = null, $page = 0) 
    {
        // jika status 0, maka surat belum ditanggapi
        if($status == 0) {
            $daftarMahasiswa = $user->getJurusan()->getRelasiMahasiswa()->whereNotIn('id', $user->getRelasiMahasiswa()->get()->pluck('id')->toArray())->where('konfirmasi', false)->where('ajukan', true)->where('mengajukan_pada', '<=', $user->created_at);
        }
        // jika status 1, maka surat telah disetujui
        else if($status == 1) {
            $daftarMahasiswa = $user->getRelasiMahasiswa()->wherePivot('disetujui', true)->orderBy('konfirmasi.updated_at', 'desc')->where('ajukan', true);
        }
        // jika status 2 atau lebih, maka surat belum disetujui
        else {          
            $daftarMahasiswa = $user->getRelasiMahasiswa()->wherePivot('disetujui', false)->orderBy('konfirmasi.updated_at', 'desc')->where('ajukan', true);
        }

        // if(!is_null($keyword)) {
        //     $daftarMahasiswa = $daftarMahasiswa->whereRaw('lower(nama) LIKE \'%'. strtolower($keyword) .'%\' OR id LIKE \'%'. $keyword .'%\'')->distinct();
        // }

        if(!is_null($keyword)) {
            $daftarMahasiswa = $daftarMahasiswa->get()->filter(function ($item) use ($keyword) {
                return strpos(strtolower($item->nama), strtolower($keyword)) > -1 || strpos($item->id, $keyword) > -1;
            })->flatten()->slice($page * 12, 12);
        } else {
            $daftarMahasiswa = $daftarMahasiswa->offset($page * 12)->take(12)->get();
        }

        if(!$additionalCounter) {
            return $daftarMahasiswa->each(function ($mahasiswa) {
                $mahasiswa['prodi'] = $mahasiswa->getProdi()->nama;
            });
        }

        return $daftarMahasiswa->each(function ($mahasiswa) {
            $mahasiswa['belum_menanggapi'] = $mahasiswa->getKalabKasublabYangBelumMenyetujui()->count();
            $mahasiswa['menyetujui'] = $mahasiswa->getKalabKasublabYangMenyetujui()->count();
            $mahasiswa['menolak'] = $mahasiswa->getKalabKasublabYangMenolak()->count();
            $mahasiswa['prodi'] = $mahasiswa->getProdi()->nama;            
        });
    }

    /**
     * mendapatkan relasi mahasiswa yang mengajukan
     * @param null $jurusan_id
     * @return mixed
     */
    public static function getRelasiYangMengajukan($jurusan_id = null)
    {
        if (is_null($jurusan_id)){
            return Mahasiswa::where('ajukan', true);
        }

        return Jurusan::find($jurusan_id)->getRelasiMahasiswa()->where('ajukan', true);
    }

    /**
     * mendapatkan data mahasiswa yang mengajukan
     * @param null $jurusan_id
     * @return mixed
     */
    public static function getYangMengajukan($jurusan_id = null)
    {
        return self::getRelasiYangMengajukan($jurusan_id)->get();
    }
    
}
