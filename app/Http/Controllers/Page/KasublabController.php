<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KasublabController extends Controller
{
    
    /**
     * Menampilkan halaman daftar mahasiswa
     * route : kasublab.daftar.mahasiswa | routes/kasublab.php
     *
     * @return \Illuminate\View\View
     */
    public function daftarMahasiswa()
    {
        return view('kasublab.daftar_mahasiswa');
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

}
