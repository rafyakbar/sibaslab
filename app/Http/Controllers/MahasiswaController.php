<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function mobileGet(Request $request)
    {
        return json_encode(Jurusan::find($request->jurusan_id)->getRelasiMahasiswa()->get());
    }
}
