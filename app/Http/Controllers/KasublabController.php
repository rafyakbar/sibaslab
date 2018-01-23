<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Prodi;
use App\User;

class KasublabController extends Controller
{

    public function __construct()
    {
        $this->middleware('kalab')->only([
            'tambah', 'hapus'
        ]);
    }
    
    /**
     * Menambah kasublab
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function tambah(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'nip' => 'required|numeric|unique:users',
            'prodi' => [
                'required',
                Rule::in(Prodi::all()->pluck('id')->toArray())
            ]
        ]);

        User::create([
            'id' => $request->nip,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi
        ]);

        return response()->json([
            'success' => 'Berhasil menambahkan kasublab !'
        ]);
    }
    
    /**
     * Menghapus kasublab
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function hapus(Request $request)
    {
        try {
            $user = User::findOrFail($request->nip);

            $user->delete();

            return response()->json([
                'success' => 'Berhasil menghapus kasublab !'
            ]);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Gagal menghapus kasublab. Tidak dapat menemukan kasublab tersebut !'
            ]);
        }
    }

    public function ubahProfil(Request $request)
    {

    }

    public function ubahKataSandi(Request $request)
    {

    }

}
