<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Prodi;
use App\Support\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function kalabKasublab()
    {
        $jurusans = Auth::user()->getFakultas()->getJurusan();
        $dosens = Auth::user()->getFakultas()->getKalabKasublab();
        return view('admin.kalabkasublab', [
            'jurusans' => $jurusans,
            'dosens' => $dosens,
            'no' => 0
        ]);
    }

    public function tambahKalabKasublab(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'nama' => 'required',
            'role' => 'required',
            'prodi' => 'required|numeric',
        ]);
        if (Prodi::find($request->prodi)->getJurusan()->getRelasiUser()->where('role', Role::KALAB)->count() > 0 || $request->role == Role::KALAB)
            return back()->withErrors([
                'Kalab per jurusan tidak boleh lebih dari satu!'
            ]);
        $user = User::create([
            'id' => $request->id,
            'nama' => $request->nama,
            'role' => $request->role,
            'prodi_id' => $request->prodi,
            'password' => bcrypt($request->id)
        ]);

        return back()->with('message', 'Berhasil menambahkan "'.$user->nama.'"".');
    }

    public function mahasiswa()
    {
        return view('admin.mahasiswa');
    }

    public function getProdi(Request $request)
    {
        return Jurusan::find($request->jurusan_id)->getProdi();
    }
}
