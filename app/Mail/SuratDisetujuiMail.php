<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Mahasiswa;

class SuratDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Pengirim dalam hal ini adalah kasublab atau kalab 
     * dengan model User
     *
     * @var User
     */
    public $pengirim;

    /**
     * Penerima dalam hal ini adalah mahasiswa
     * dengan model Mahasiswa 
     *
     * @var Mahasiswa
     */
    public $penerima;

    /**
     * Konstruktor
     *
     * @param User $pengirim
     * @param Mahasiswa $penerima
     */
    public function __construct($pengirim, $penerima)
    {
        $this->pengirim = $pengirim;
        $this->penerima = $penerima;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.surat_disetujui', [
            'pengirim' => $this->pengirim
        ]);
    }

}
