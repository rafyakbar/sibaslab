<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Prodi;
use App\User;
use App\Support\Role;

class KasublabController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:' . Role::KALAB)->only([
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
            'nip' => 'required|numeric|unique:users,id',
            'prodi' => [
                'required',
                Rule::in(Prodi::all()->pluck('id')->toArray())
            ]
        ]);

        User::create([
            'id' => $request->nip,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi,
            'password' => bcrypt('secret'),
            'role' => Role::KASUBLAB
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
    
    /**
     * Mengubah kata sandi
     *
     * @param Request $request
     * @return void
     */
    public function ubahKataSandi(Request $request)
    {
        if(!Hash::check($request->passlama, Auth::user()->password)) {
            return back()->with([
                'error' => 'Kata sandi lama anda salah !'
            ]);
        }
        else {
            if($request->passbaru !== $request->passbaru_confirmation) {
                return back()->with([
                    'error' => 'Kata sandi yang anda masukkan tidak sama !'
                ]); 
            }
            else {
                if (Auth::user()->role == Role::KETUA_KPU) {
                    Auth::user()->update([
                        'password' => bcrypt($request->passbaru),
                        'helper' => bcrypt($request->passbaru)
                    ]);
                } else {
                    Auth::user()->update([
                        'password' => bcrypt($request->passbaru)
                    ]);
                }

                return back()->with([
                    'success' => 'Berhasil mengubah kata sandi 1 !'
                ]);
            }
        }
    }

}
