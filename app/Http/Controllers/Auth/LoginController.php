<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mahasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller {

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->only([
            'showLoginForm'
        ]);
    }

    /**
     * validasi login
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'password'=>'required'
        ]);
    }

    /**
     * memodifikasi credential
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('id','password');
    }

    /**
     * memodifikasi attempt
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        else if ($user->isKalab() || $user->isKasublab()) {
            return redirect()->route('kasublab.daftar.mahasiswa');
        }

        return redirect('/');
    }

    protected function loginMahasiswa(Request $request)
    {
        try {
           $this->validate($request, [
               'id'=> 'required|numeric',
               'password' => 'required'
           ]);

           $mahasiswa = Mahasiswa::findOrFail($request->id);
           if(Hash::check($request->password, $mahasiswa->password))
           {
               Auth::guard('mhs')->login($mahasiswa);
               return redirect()->route('mahasiswa.dashboard');
           }

           return back()->with('message','NIM atau password yang anda masukkan salah');
        }
        catch (ModelNotFoundException $err) {
            return back()->with('message','NIM atau password yang anda masukkan salah');
        }
    }

    /**
     * Melakukan proses logout
     * route: user.logout (post)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if(Auth::guard('mhs')->check()) {
            Auth::guard('mhs')->logout();
            return redirect()->route('mahasiswa.login');
        }

        Auth::logout();
        return redirect()->route('user.login');
    }

}
