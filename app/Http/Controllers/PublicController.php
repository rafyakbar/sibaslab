<?php

namespace App\Http\Controllers;

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
}
