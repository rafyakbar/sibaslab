<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Support\Role;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{

    public function __construct()
    {
        
    }
    
    /**
     * Mengubah profil pengguna
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ubahProfil(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required'
        ]);

        if(Auth::guard('web')->check() && Auth::user()->isKasublab() || Auth::user()->isKalab()) {
            $this->validate($request, [
                'prodi' => [
                    'required',
                    Rule::in(Auth::user()->getJurusan()->getProdi()->pluck('id')->toArray())
                ]
            ]);

            Auth::user()->update([
                'prodi_id' => $request->prodi
            ]);
        }

        Auth::user()->update([
            'nama' => $request->nama
        ]);

        return back()->with([
            'success' => 'Berhasil mengubah profil'
        ]);
    }

    /**
     * Mengubah kata sandi
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ubahKataSandi(Request $request)
    {
        if(!Hash::check($request->passlama, Auth::user()->password)) {
            return back()->withErrors([
                'passlama' => 'Kata sandi lama anda salah !'
            ]);
        }
        else {
            if($request->passbaru !== $request->passbaru_confirmation) {
                return back()->withErrors([
                    'passbaru_confirmation' => 'Kata sandi yang anda masukkan tidak sama !'
                ]); 
            }
            else {
                Auth::user()->update([
                    'password' => bcrypt($request->passbaru)
                ]);

                return back()->with([
                    'success_password' => 'Berhasil mengubah kata sandi !'
                ]);
            }
        }
    }

}
