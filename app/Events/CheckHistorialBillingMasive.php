<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckHistorialBillingMasive implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $suscripcion;
    protected $total_esperado;
    protected $total_real;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($suscripcion, $total_esperado, $total_real)
    {
        Log::info('instanciando evento');
        $this->suscripcion = $suscripcion;
        $this->total_esperado = $total_esperado;
        $this->total_real = $total_real;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('canal');
        return new Channel('home');
    }
}
