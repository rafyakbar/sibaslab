<?php

namespace App\Listeners;

use App\Events\SuratBelumDisetujui;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Fadaces\Mail;
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

        Mail::to($penerima)->send(new SuratBelumDisetujuiMail($pengirim));
    }

}
