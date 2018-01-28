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

    public function prosesAjukan(Request $request)
    {
        $this->validate($request, [

            'berkas' => 'required|file|mimes:pdf'
        ]);

        $berkas = $request->file('berkas');

        $path = $berkas->store('public/berkas');

        if(Auth::guard('mhs')->check())
        {
            Auth::guard('mhs')->user()->update([
                'dir' => $path
            ]);
            return back()->with('message', 'Berhasil memperbarui data');
        }
        Mahasiswa::create([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi,
            'dir' => $path,
            'password' => bcrypt($request->nim),
            'id' => $request->nim
        ]);
        return back()->with('message', 'Berhasil memperbarui data');
    }

    public function dashboard()
    {
        $kasublabMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangMenyetujui();
        $kasublabBelumMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangBelumMenyetujui();
        $kasublabMenolak = Auth::guard('mhs')->user()->getKalabKasublabYangMenolak();

        return view('mahasiswa.dashboard', [
            'kasublabMenyetujui' => $kasublabMenyetujui,
            'kasublabBelumMenyetujui' => $kasublabBelumMenyetujui,
            'kasublabMenolak' => $kasublabMenolak]);
    }

    public function login()
    {
        return view('mahasiswa.login');
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
        $penyetuju = Auth::user();

        if($penyetuju->doKonfirmasi($request->nim, true)) {
            return response()->json([
                'success' => 'Berhasil menyetujui'
            ]);
        }

        return response()->json([
            'error' => 'Semua kasublab belum menyetujui'
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
        $penyetuju = Auth::user();

        if($penyetuju->doKonfirmasi($request->nim, false, $request->catatan)) {
            return response()->json([
                'success' => 'Berhasil mengirim catatan !'
            ]);
        }
    }

}
