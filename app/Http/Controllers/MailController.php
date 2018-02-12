<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{
    //
    public function kirimEmail()
    {
        $data=['name' => 'testing'];
        Mail::send('emails.mail', $data, function($message) {
	    $message->to('iskandarjava@gmail.com', 'Artisans Web')
	            ->subject('Pengajuan Surat Bebas Laboratorium');
	    $message->from('bebaslabunesa@gmail.com','Bebas Lab Fakultas Teknik Unesa');
	});
    }
}
