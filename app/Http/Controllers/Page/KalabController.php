<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KalabController extends Controller
{
    
    /**
     * Menampilkan halaman daftar kasublab, halaman ini hanya bisa diakses oleh
     * kalab
     *
     * @return \Illuminate\View\View
     */
    public function daftarKasublab()
    {
        $daftarKasublab = Auth::user()->getJurusan()->getDaftarKasublab();

        $daftarKasublab = $daftarKasublab->each(function ($kasublab) {
            $kasublab['belum_menanggapi'] = Mahasiswa::getMahasiswaByStatus($kasublab, 0, false)->count();
            $kasublab['menyetujui'] = Mahasiswa::getMahasiswaByStatus($kasublab, 1, false)->count();
            $kasublab['menolak'] = Mahasiswa::getMahasiswaByStatus($kasublab, 2, false)->count();
        });

        return view('kasublab.daftar_kasublab', [
            'daftarKasublab' => $daftarKasublab,
            'daftarProdi' => Auth::user()->getJurusan()->getProdi()
        ]);
    }
    
}
