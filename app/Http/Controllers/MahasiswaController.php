<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Mahasiswa;
use App\Prodi;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Support\Role;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\SuratBelumDisetujui;
use App\Events\SuratDisetujui;

class MahasiswaController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('ajax')->only([
            'setujuiSurat', 'tolakSurat', 'loadMoreMahasiswa'
        ]);

        $this->middleware('kalabOrKasublab')->only([
            'loadMoreMahasiswa'
        ]);

        $this->middleware('guest:mhs')->only([
            'login', 'ajukan', 'prosesAjukan'
        ]);

        $this->middleware('role:' . Role::KALAB)->only([
            'tambah', 'hapus'
        ]);

        $this->middleware('guest:web')->only([
            'login', 'ajukan'
        ]);
    }

    public function kirimEmailKeKalabKasublab()
    {
        $data=['name' => Auth::guard('mhs')->user()->nama];
        Mail::send('emails.mail', $data, function($message) {
            $message->to('rafy683@gmail.com', 'Artisans Web')
                ->subject('Pengajuan Surat Bebas Laboratorium');
            $message->from('bebaslabunesa@gmail.com','Bebas Lab Fakultas Teknik Unesa');
        });
    }

    public function unduh()
    {
        $jurusan = Auth::guard('mhs')->user()->getJurusan();
        $prodi = Auth::guard('mhs')->user()->getProdi();
        $kasublab = Auth::guard('mhs')->user()->getKasublab();
        $kalab = Auth::guard('mhs')->user()->getKalab();

        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setHeight(256);
        $renderer->setWidth(256);
        $renderer->setMargin(0);
        $writer = new \BaconQrCode\Writer($renderer);
        $namaQr = Auth::guard('mhs')->user()->id;
        $val = route('mahasiswa.validasi', ['val' => encrypt(Auth::guard('mhs')->user()->id)]);
        $writer->writeFile($val, 'images/qrCode/'.$namaQr.'.png');

        $d = PDF::loadView('mahasiswa.surat-bebas-lab', [
            'jurusan' => $jurusan,
            'prodi' => $prodi,
            'qr' => QrCode::size(300)->generate('Smadia'),
            'semuaKasublab' => $kasublab,
            'kalab' => $kalab])->download('Surat bebas lab '.Auth::guard('mhs')->user()->nama.'.pdf');
        File::delete(public_path('images/qrCode/'.$namaQr.'.png'));
        return $d;
    }

    public function edit()
    {
        return view('mahasiswa.edit');
    }

    public function perbaruiPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required'
        ]);

        if(!Hash::check($request->password, Auth::guard('mhs')->user()->password))
        {
            return back()->with([
                'error' =>  'Kata sandi lama anda salah !'
            ]);
        }
        else
        {
            if($request->newPassword !== $request->confirmNewPassword)
            {
                return back()->with([
                    'error' =>  'Konfirmasi password tidak sama dengan password baru !'
                ]);
            }
            else if(Hash::check($request->newPassword, Auth::guard('mhs')->user()->password))
            {
                return back()->with([
                    'error' =>  'Password baru dan password lama tidak boleh sama !'
                ]);
            }
            else
            {
                Auth::guard('mhs')->user()->update([
                    'password' => bcrypt($request->newPassword)
                ]);
                return back()->with([
                    'success' => 'Berhasil mengubah kata sandi !'
                ]);
            }
        }
    }

    /**
     * Mendapatkan daftar mahasiswa dengan offset tertentu yang akan
     * ditampilkan ketika pengguna menekan tombol lihat lainnya
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadMoreMahasiswa(Request $request)
    {
        $status = (int) $request->status;
        $countLoaded = (int) $request->count_loaded;
        $keyword = $request->keyword == 'null' ? null : $request->keyword;

        $daftarMahasiswa = Mahasiswa::getMahasiswaByStatus(Auth::user(), $status, true, $keyword, $countLoaded / 12);

        $daftarMahasiswa = $daftarMahasiswa->map(function ($item) {
            return $item->setHidden(['password']);
        });

        return response()->json($daftarMahasiswa->toArray());
    }

    public function perbaruiBerkas(Request $request)
    {
        if (Auth::guard('mhs')->check()) {
            $this->validate($request, [
                'berkas' => 'required|file|mimes:pdf'
            ]);

            $namaBerkas = Auth::guard('mhs')->user()->dir;

            $berkas = $request->file('berkas');

            $path = $berkas->store('public/berkas');

            Auth::guard('mhs')->user()->update([
                'dir' => $path
            ]);

            Storage::delete($namaBerkas);

            return back()->with('success', 'Berhasil memperbarui berkas');
        }
    }

    public function prosesAjukan(Request $request)
    {
//        $this->validate($request, [
//            'nama' => 'required',
//            'nim' => 'required|numeric|unique:mahasiswa,id',
//            'prodi' => 'required|numeric',
//            'berkas' => 'nullable|file|mimes:pdf|max:2000'
//        ]);
//
//        if($request->berkas!=null)
//        {
//            $berkas = $request->file('berkas');
//            $path = $berkas->store('public/berkas');
//
//            $mhs = Mahasiswa::create([
//                'nama' => $request->nama,
//                'prodi_id' => $request->prodi,
//                'dir' => $path,
//                'password' => bcrypt($request->nim),
//                'id' => $request->nim
//            ]);
//        }
//        else
//        {
//            $mhs = Mahasiswa::create([
//                'nama' => $request->nama,
//                'prodi_id' => $request->prodi,
//                'password' => bcrypt($request->nim),
//                'id' => $request->nim
//            ]);
//        }
        $this->validate($request, [
            'nim' => 'required|numeric'
        ]);

        $mahasiswa = Mahasiswa::findOrFail($request->nim);
        if ($mahasiswa->ta == null)
        {
            return back()->with('message','NIM yang anda masukkan tidak terdaftar');
        }
        else if($mahasiswa->ajukan == true)
        {
            return back()->with('message','NIM yang anda masukkan telah diajukan');
        }
        else if($mahasiswa->ajukan == false)
        {
            $prodi = $mahasiswa->getProdi()->nama;
            return view('mahasiswa.konfirmasi', [
                'mhs' => $mahasiswa,
                'prodi' => $prodi
            ]);
        }
        else
        {
            return back()->with('message','NIM yang anda masukkan tidak terdaftar');
        }


    }

    public function konfirmasiAjukan(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail($request->nim);

        if ($mahasiswa->ta == null)
        {
            return back()->with('message','NIM yang anda masukkan tidak terdaftar');
        }
        else if($mahasiswa->ajukan == true)
        {
            return back()->with('message','NIM yang anda masukkan telah diajukan');
        }
        else if($mahasiswa->ajukan == false)
        {

            $this->kirimEmailKeKalabKasublab();

            Auth::guard('mhs')->user()->update([
                'ajukan' => true
            ]);

            Auth::guard('mhs')->login($mahasiswa);

            return redirect(URL::route('mahasiswa.dashboard', [], false))->with('message', 'Gunakan NIM anda untuk password sementara dan segera perbarui password anda !');
        }
        else
        {
            return back()->with('message','NIM yang anda masukkan tidak terdaftar');
        }
    }

    public function dashboard()
    {
        if(!Auth::guard('mhs')->check())
        {
            return view('mahasiswa.login');
        }

        $kasublabMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangMenyetujui();
        $kasublabBelumMenyetujui = Auth::guard('mhs')->user()->getKalabKasublabYangBelumMenyetujui();
        $kasublabMenolak = Auth::guard('mhs')->user()->getKalabKasublabYangMenolak();

        $jumlahMenyetujui = $kasublabMenyetujui->count();
        $jumlahMenolak = $kasublabMenolak->count();
        $jumlahBelum = $kasublabBelumMenyetujui->count();

        return view('mahasiswa.dashboard', [
            'kasublabMenyetujui' => $kasublabMenyetujui,
            'kasublabBelumMenyetujui' => $kasublabBelumMenyetujui,
            'kasublabMenolak' => $kasublabMenolak,
            'jumlahMenyetujui' => $jumlahMenyetujui,
            'jumlahMenolak' => $jumlahMenolak,
            'jumlahBelum' => $jumlahBelum]);
    }

    /**
     * Menampilkan halaman login untuk mahasiswa
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('mahasiswa.login');
    }

    public function ajukan(Request $request)
    {
//        $jurusan = Jurusan::all();
//        $prodi = Prodi::all();
//        return view('mahasiswa.ajukan', [
//            'semuaJurusan' => $jurusan,
//            'semuaProdi' => $prodi]);
        return view('mahasiswa.ajukan');
    }

    /**
     * Melakukan proses pengunduhan berkas
     *
     * @param Request $request
     * @return mixed
     */
    public function unduhBerkas(Request $request)
    {
        $file = Mahasiswa::find($request->nim)->dir;

        return response()->download(
            storage_path('app/'. $file)
        );
    }

    /**
     * Menyetujui surat untuk mahasiswa tertentu
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function setujuiSurat(Request $request)
    {
        $penyetuju = Auth::user();
        $mahasiswa = Mahasiswa::find($request->nim);

        if($penyetuju->doKonfirmasi($mahasiswa->id, true)) {

            event(new SuratDisetujui($penyetuju, $mahasiswa));

            return response()->json([
                'success' => 'Berhasil menyetujui'
            ]);
        }

        return response()->json([
            'error' => 'Semua kasublab belum menyetujui'
        ]);
    }

    /**
     * Menolak penyetujuan surat dengan menambahkan catatan tertentu
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function tolakSurat(Request $request)
    {
        $penyetuju = Auth::user();
        $mahasiswa = Mahasiswa::find($request->nim);        

        if($penyetuju->doKonfirmasi($mahasiswa->id, false, $request->catatan)) {

            event(new SuratBelumDisetujui($penyetuju, $mahasiswa));
            
            return response()->json([
                'success' => 'Berhasil mengirim catatan !'
            ]);
        }

        return response()->json([
            'error' => 'Semua kasublab belum menyetujui'
        ]);
    }

    /**
     * Mendapatkan catatan dari kasublab atau kalab tertentu
     * terhadap mahasiswa tertentu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lihatCatatan(Request $request)
    {
        try {

            $mahasiswa = Mahasiswa::findOrFail($request->nim);
            $catatan = $mahasiswa->getRelasiUser()->where('id', Auth::user()->id)->first()->pivot->catatan;

            return response()->json([
                'catatan' => $catatan
            ]);

        } catch(ModelNotFoundException $err) {
            return response()->json([
                'error' => 'Tidak menemukan mahasiswa tersebut'
            ]);
        }
    }

    /**
     * Mendapatkan daftar kasublab dan kalab yang belum
     * memberi tanggapan terhadap sebuah surat
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDaftarBelumMenanggapi(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);

        return response()->json($mahasiswa->getKalabKasublabYangBelumMenyetujui()->toArray());
    }

    /**
     * Mendapatkan daftar kasublab dan kalab yang menyetujui surat tertentu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDaftarMenyetujui(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);

        return response()->json($mahasiswa->getKalabKasublabYangMenyetujui()->toArray());
    }

    /**
     * Mendapatkan dfatar kasublab dan kalab yang belum menyetujui surat tertentu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDaftarBelumMenyetujui(Request $request)
    {
        $mahasiswa = Mahasiswa::find($request->nim);

        return response()->json($mahasiswa->getKalabKasublabYangMenolak()->toArray());
    }

    /**
     * Mencari mahasiswa oleh kasublab berdasarkan nama atau nim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cariMahasiswa(Request $request)
    {
        $keyword = $request->keyword;
        $status = $request->status;

        return response()->json(Mahasiswa::getMahasiswaByStatus(
            Auth::user(),
            $status,
            true,
            $keyword
        ));
    }

    /**
     * Menampilkan qr code dari nim mahasiswa
     *
     * @param string $nim
     * @return mixed
     */ 
    public function tampilQr($nim)
    {
        try {
            $dir_qr = Mahasiswa::find($nim)->id;

            return response()->file(storage_path('app/public/qrCode/images/' . $dir_qr . '.png'));
        } catch (ModelNotFoundException $err) {
            return 'Tidak dapat menemukan qr code';
        }
    }

}
