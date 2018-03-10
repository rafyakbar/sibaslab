<?php

namespace App\Http\Controllers;

use App\Fakultas;
use App\Jurusan;
use App\Prodi;
use App\Mahasiswa;
use App\Support\ApiUnesa;
use App\Support\Role;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Ixudra\Curl\Facades\Curl;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:' . Role::ADMIN);
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
            'email' => 'required'
        ]);
        if (Prodi::find($request->prodi)->getJurusan()->getRelasiUser()->where('role', Role::KALAB)->count() > 0 && $request->role == Role::KALAB)
            return back()->withErrors([
                'Kalab per jurusan tidak boleh lebih dari satu!'
            ]);
        $user = User::create([
            'id' => $request->id,
            'nama' => $request->nama,
            'email' => $request->email,
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
        $mhs = Mahasiswa::whereRaw("id ILIKE '%".$request->id."%' or nama ILIKE '%".$request->id."%'")->paginate(10);
        return view('admin.mahasiswa', [
            'mhs' => $mhs,
            'q' => ($request->id == '[]') ? null : $request->id
        ]);
    }

    public function resetUser(Request $request)
    {
        try {
            User::findOrFail($request->id)->update([
                'password' => bcrypt($request->id)
            ]);

            return back()->with('message', 'Berhasil mereset sandi pengguna');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['Pengguna tidak ditemukan']);
        }
    }

    public function resetMahasiswa(Request $request)
    {
        try {
            Mahasiswa::findOrFail($request->id)->update([
                'password' => bcrypt($request->id)
            ]);

            return back()->with('message', 'Berhasil mereset sandi mahasiswa');
        } catch (ModelNotFoundException $e) {
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

    public function ubahKalabTambahKasublab(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        $user = User::find($request->id);
        if (Auth::user()->getFakultas()->id == $user->getFakultas()->id && $user->isKalab()) {
            $user->tambah_kasublab = !$user->tambah_kasublab;
            $user->save();

            return back()->with('message', 'Berhasil mengubah data');
        }

        return back()->withErrors(['Pengguna tersebut bukan dari Fakultas ' . Auth::user()->getFakultas()->nama . ' atau bukan Kalab']);
    }

    public function sinkron()
    {
        $jummhsupdate = 0;
        $jummhsbaru = 0;
        $jumjurbaru = 0;
        $jumprdbaru = 0;
        $jurusans = ApiUnesa::getFakultasJurusanProdi()->filter(function ($value, $key){
            return strtolower($value->nama) == strtolower(Auth::user()->getFakultas()->nama);
        })->first()->child;
        foreach ($jurusans as $jurusan){
            $j = Jurusan::findByName($jurusan->nama);
            if (is_null($j)){
                $j = Jurusan::create([
                    'nama' => $jurusan->nama,
                    'fakultas_id' => Auth::user()->getFakultas()->id
                ]);
                $jumjurbaru++;
            }
            foreach ($jurusan->child as $keyprodi => $prodi){
                $p = Prodi::findByName($prodi);
                if (is_null($p)){
                    $p = Prodi::create([
                        'nama' => $prodi,
                        'jurusan_id' => $j->id
                    ]);
                    $jumprdbaru++;
                }
                $filteredmhs = ApiUnesa::getMahasiswaPerProdi($keyprodi)
                    ->filter(function ($value, $key){
                        return !is_null($value->n_skripsi->n);
                    });
                foreach ($filteredmhs as $keymhs => $mhs){
                    $mahasiswa = Mahasiswa::find($keymhs);
                    if (!is_null($mahasiswa)){
                        $mahasiswa->update([
                            'nama' => $mhs->nama_mahasiswa,
                            'ipk' => $mhs->aktivitas_kuliah->ipk,
                            'ta' => $mhs->n_skripsi->n
                        ]);
                        $jummhsupdate++;
                    }
                    else{
                        Mahasiswa::create([
                            'id' => $keymhs,
                            'prodi_id' => $p->id,
                            'nama' => $mhs->nama_mahasiswa,
                            'ipk' => $mhs->aktivitas_kuliah->ipk,
                            'email' => $mhs->email,
                            'jk' => $mhs->jenis_kelamin,
                            'ta' => $mhs->n_skripsi->n,
                            'password' => bcrypt($keymhs)
                        ]);
                        $jummhsbaru++;
                    }
                }
            }
        }

        return back()->with('message', 'Berhasil memperbarui '.$jummhsupdate.' mahasiswa, menambah '.$jummhsbaru.' mahasiswa baru, menambah '.$jumjurbaru.' jurusan baru dan menambah '.$jumprdbaru.' prodi baru!');
    }
}
