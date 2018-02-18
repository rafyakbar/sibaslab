<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mahasiswa;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $daftarMahasiswa = $daftarMahasiswa->map(function ($item) {
            return $item->setHidden(['password', 'remember_token', 'updated_at', 'validasi', 'created_at', 'prodi_id', 'pivot']);
        });

        return view('kasublab.daftar_mahasiswa', [
            'daftarMahasiswa' => $daftarMahasiswa->take(12)
        ]);
    }

}
