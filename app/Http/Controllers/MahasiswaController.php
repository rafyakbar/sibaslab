<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{

    public function __construct()
    {
        $this->middleware('ajax')->only([
            'setujuiSurat', 'tolakSurat'
        ]);
    }

    public function dashboard()
    {
        return view('mahasiswa.dashboard');
    }

    public function login()
    {
        return view('mahasiswa.login');
    }

    public function mobileGet(Request $request)
    {
        return response()->json((Jurusan::find($request->jurusan_id)->getRelasiMahasiswa()->get()));
    }

    public function ajukan(Request $request)
    {
        return view('mahasiswa.ajukan');
    }

    /**
     * Menyetujui surat untuk mahasiswa tertentu
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function setujuiSurat(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);
        $penyetuju = Auth::user();

        $mahasiswa->getRelasiUser()->attach($penyetuju, [
            'disetujui' => true
        ]);

        return response()->json([
            'success' => 'Berhasil menyetujui'
        ]);
    }

    /**
     * Menolak penyetujuan surat dengan menambahkan catatan tertentu
     * 
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function tolakSurat(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);
        $penyetuju = Auth::user();

        $mahasiswa->getRelasiUser()->attach($penyetuju, [
            'disetujui' => false,
            'catatan' => $request->catatan
        ]);

        return response()->json([
            'success' => 'Berhasil mengirim catatan'
        ]);
    }

}
