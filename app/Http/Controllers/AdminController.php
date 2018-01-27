<?php

namespace App\Http\Controllers;

use App\Jurusan;
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

    public function mahasiswa()
    {
        return view('admin.mahasiswa');
    }

    public function getProdi(Request $request)
    {
        return Jurusan::find($request->jurusan_id)->getProdi();
    }
}
