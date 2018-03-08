<?php

namespace App\Listeners;

use App\Events\SuratBelumDisetujui;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\Mail;
use App\Mail\SuratBelumDisetujuiMail;

class KirimEmailBelumDisetujui
{
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SuratBelumDisetujui  $event
     * @return void
     */
    public function handle(SuratBelumDisetujui $event)
    {
        $penerima = $event->penerima->email;
        $pengirim = $event->pengirim->email;
        $catatan = $this->pengirim->getRelasiMahasiswa()->where('id', $this->penerima->id)->first()->pivot->catatan;

        Mail::to($penerima)->send('emails.surat_belum_disetujui', [
            'pengirim' => $this->pengirim,
            'catatan' => $catatan
        ]);
    }

}
