<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mahasiswa;

class KasublabController extends Controller
{
    
    /**
     * Menampilkan halaman daftar mahasiswa
     * route : kasublab.daftar.mahasiswa | routes/kasublab.php
     *
     * @return \Illuminate\View\View
     */
    public function daftarMahasiswa(Request $request)
    {
        $status = (int) $request->get('status');
        
        $daftarMahasiswa = Mahasiswa::getMahasiswaByStatus(Auth::user(), $status);

        return view('kasublab.daftar_mahasiswa', [
            'daftarMahasiswa' => $daftarMahasiswa->map(function ($item) {
                return $item->setHidden(['password']);
            })
        ]);
    }

    /**
     * Menampilkan halaman pengaturan untuk kasublab maupun kalab
     * route : kasublab.pengaturan | routes/kasublab.php
     * 
     * @return \Illuminate\View\View
     */
    public function pengaturan()
    {
        return view('kasublab.pengaturan');
    }

    /**
     * Menampilkan halaman daftar kasublab, halaman ini hanya bisa diakses oleh
     * kalab
     *
     * @return \Illuminate\View\View
     */
    public function daftarKasublab()
    {
        $daftarKasublab = Auth::user()->getJurusan()->getDaftarKasublab();

        return view('kasublab.daftar_kasublab', [
            'daftarKasublab' => $daftarKasublab
        ]);
    }

}
