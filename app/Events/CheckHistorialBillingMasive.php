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

    public $suscripcion;
    public $total_esperado;
    public $total_real;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id_suscripcion, $total_esperado, $total_real, $status = 'En Proceso')
    {
        Log::info('instanciando evento');
        $this->suscripcion = $id_suscripcion;
        $this->total_esperado = $total_esperado;
        $this->total_real = $total_real;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info('canal');
        //return ['home'];
        //return new Channel('home');
        return new PrivateChannel('historial-suscripcion.'.$this->suscripcion);
    }
}
