<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use App\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{

    public function __construct()
    {
        $this->middleware('ajax')->only([
            'setujuiSurat', 'tolakSurat'
        ]);
    }

    public function edit()
    {
        return view('mahasiswa.edit');
    }

    public function perbaruiPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required'
        ]);

        if(!Hash::check($request->password, Auth::guard('mhs')->user()->password))
        {
            return back()->with([
                'error' =>  'Kata sandi lama anda salah !'
            ]);
        }
        else
        {
            if($request->newPassword !== $request->confirmNewPassword)
            {
                return back()->with([
                    'error' =>  'Konfirmasi password tidak sama dengan password baru !'
                ]);
            }
            else if($request->newPassword == Auth::guard('mhs')->user()->password)
            {
                return back()->with([
                    'error' =>  'Password baru dan password lama tidak boleh sama !'
                ]);
            }
            else
            {
                Auth::guard('mhs')->user()->update([
                    'password' => bcrypt($request->newPassword)
                ]);
                return back()->with([
                    'success' => 'Berhasil mengubah kata sandi !'
                ]);
            }
        }
    }

    public function olahData(Request $request)
    {
        if (Auth::guard('mhs')->check()) {
            $this->validate($request, [
                'berkas' => 'required|file|mimes:pdf'
            ]);

            $berkas = $request->file('berkas');

            $path = $berkas->store('public/berkas');

            Auth::guard('mhs')->user()->update([
                'dir' => $path
            ]);
            return back()->with('message', 'Berhasil memperbarui data');
        }
        else
        {
            $this->validate($request, [
                'nama' => 'required',
                'nim' => 'required|numeric|unique:mahasiswa,id',
                'prodi' => 'required|numeric',
                'berkas' => 'required|file|mimes:pdf'
            ]);

            $berkas = $request->file('berkas');

            $path = $berkas->store('public/berkas');

            $mhs = Mahasiswa::create([
                'nama' => $request->nama,
                'prodi_id' => $request->prodi,
                'dir' => $path,
                'password' => bcrypt($request->nim),
                'id' => $request->nim
            ]);

            Auth::guard('mhs')->login($mhs);

            return back()->with('message', 'Berhasil Mengajukan Surat Bebas Lab');
        }

    }

    public function dashboard()
    {
        if(!Auth::guard('mhs')->check())
        {
            return view('mahasiswa.login');
        }

        $kasublabMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangMenyetujui();
        $kasublabBelumMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangBelumMenyetujui();
        $kasublabMenolak = Auth::guard('mhs')->user()->getKalabKasublabYangMenolak();

        $jumlahMenyetujui = $kasublabMenyetujui->count();
        $jumlahMenolak = $kasublabMenolak->count();
        $jumlahBelum = $kasublabBelumMenyetujui->count();

        return view('mahasiswa.dashboard', [
            'kasublabMenyetujui' => $kasublabMenyetujui,
            'kasublabBelumMenyetujui' => $kasublabBelumMenyetujui,
            'kasublabMenolak' => $kasublabMenolak,
            'jumlahMenyetujui' => $jumlahMenyetujui,
            'jumlahMenolak' => $jumlahMenolak,
            'jumlahBelum' => $jumlahBelum]);
    }

    public function login()
    {
        return view('mahasiswa.login');
    }

    public function ajukan(Request $request)
    {
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        return view('mahasiswa.ajukan', [
            'semuaJurusan' => $jurusan,
            'semuaProdi' => $prodi]);
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

        return response()->json([
            'error' => 'Semua kasublab belum menyetujui'
        ]);
    }

}
