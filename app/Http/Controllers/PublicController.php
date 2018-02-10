<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Jurusan;
use App\Prodi;

class PublicController extends Controller
{
    //
    public function getProdi(Request $request)
    {
        return Jurusan::find($request->jurusan_id)->getProdi();
    }

    public function validasi(Request $request)
    {
        try{
            $mhs = Mahasiswa::findOrFail(decrypt($request->val));
            return view('mahasiswa.validasi', [
                'mhs' => $mhs
            ]);
        }
        catch (ModelNotFoundException $exception){
            return 'Mahasiswa tidak ditemukan!';
        }
    }
}
