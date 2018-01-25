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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
        if ($request->user()->isAdmin()) {
            return redirect()->intended($this->redirectPath());
        }
        else if ($request->user()->isKalab() || $request->user()->isKasublab()) {
            return redirect()->route('kasublab.daftar.mahasiswa');
        }
    }

    protected function loginMahasiswa(Request $request)
    {
        try {
           $this->validate($request, [
               'id'=> 'required|numeric',
               'password' => 'required'
           ]);

           $mahasiswa = Mahasiswa::findOrFail($request->id);
           if(Hash::check($request->password, $mahasiswa->token)) {
               Auth::guard('mhs')->login($mahasiswa);
               return redirect()->route('mahasiswa.dashboard');
           }

           return back()->with('message','NIM atau password yang anda masukkan salah');
        }
        catch (ModelNotFoundException $err) {
            return back()->with('message','NIM atau password yang anda masukkan salah');
        }
    }

}
