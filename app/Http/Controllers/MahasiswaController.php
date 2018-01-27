<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{

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
            'password' => bcrypt($request->nim)
        ]);
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
        $mahasiswa = Mahasiswa::find($request->nim);
        $penyetuju = Auth::user();

        $mahasiswa->getRelasiKonfirmasiUser()->attach($penyetuju, [
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

        $mahasiswa->getRelasiKonfirmasiUser()->attach($penyetuju, [
            'disetujui' => false,
            'catatan' => $request->catatan
        ]);

        return response()->json([
            'success' => 'Berhasil mengirim catatan'
        ]);
    }

}
