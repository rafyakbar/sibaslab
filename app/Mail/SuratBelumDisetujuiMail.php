<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuratBelumDisetujuiMail extends Mailable
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
        $catatan = $pengirim->getRelasiMahasiswa()->where('id', $this->penerima->id)->first()->pivot->catatan;
        
        return $this->view('emails.surat_belum_disetujui', [
            'pengirim' => $this->pengirim,
            'catatan' => $catatan
        ]);
    }

}
