<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Mahasiswa;

class SuratDisetujui
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
