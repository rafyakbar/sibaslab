<?php

namespace App\Http\Controllers;

use App\Fakultas;
use App\Jurusan;
use App\Mahasiswa;
use App\Support\Role;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:'.Role::ADMIN);
    }

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

    public function etc()
    {
        $kalabs = Auth::user()->getFakultas()->getKalab();
        return view('admin.etc', [
            'kalabs' => $kalabs,
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
        if (Prodi::find($request->prodi)->getJurusan()->getRelasiUser()->where('role', Role::KALAB)->count() > 0 && $request->role == Role::KALAB)
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

        return back()->with('message', 'Berhasil menambahkan.');
    }

    public function hapusKalabKasublab(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        User::find($request->id)->delete();

        return back()->with('message', 'Berhasil mengahapus pengguna');
    }

    public function mahasiswa(Request $request)
    {
        $request->jurusan = ($request->jurusan != 'Semua') ? (!Jurusan::checkByName($request->jurusan, Auth::user()->getFakultas()->id)) ? 'Semua' : $request->jurusan : 'Semua';
        return view('admin.mahasiswa', [
            'jurusan' => $request->jurusan,
            'jurusans' => Auth::user()->getFakultas()->getJurusan()
        ]);
    }

    public function getMahasiswa(Request $request)
    {
        $mahasiswa = ($request->jurusan == 'Semua') ? Auth::user()->getFakultas()->getMahasiswa() : Jurusan::findByName($request->jurusan)->getMahasiswa();
        return DataTables::of($mahasiswa)->addColumn('jurusan', function (Mahasiswa $mahasiswa){
            return $mahasiswa->getJurusan()->nama;
        })->addColumn('aksi', function (Mahasiswa $mahasiswa){
            return '<button class="btn btn-warning btn-sm text-light" onclick="reset('.$mahasiswa->id.')">Reset password</button>';
        })->rawColumns(['aksi'])->make(true);
    }

    public function resetUser(Request $request)
    {
        try
        {
            User::findOrFail($request->id)->update([
                'password' => bcrypt($request->id)
            ]);

            return back()->with('message', 'Berhasil mereset sandi pengguna');
        }
        catch (ModelNotFoundException $e){
            return back()->withErrors(['Pengguna tidak ditemukan']);
        }
    }

    public function resetMahasiswa(Request $request)
    {
        try
        {
            Mahasiswa::findOrFail($request->id)->update([
                'password' => bcrypt($request->id)
            ]);

            return back()->with('message', 'Berhasil mereset sandi mahasiswa');
        }
        catch (ModelNotFoundException $e){
            return back()->withErrors(['Mahasiswa tidak ditemukan']);
        }
    }

    public function updateLink(Request $request)
    {
        $this->validate($request, [
            'link' => 'required'
        ]);

        Auth::user()->getFakultas()->update([
            'link' => $request->link
        ]);

        return back()->with('message', 'Link berhasil diupdate');
    }
}
