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
     * Konstruktor
     *
     * @param User $pengirim
     */
    public function __construct($pengirim)
    {
        $this->pengirim = $pengirim;
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
