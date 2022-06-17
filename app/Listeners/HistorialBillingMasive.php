<?php

namespace App\Listeners;

use App\Events\CheckHistorialBillingMasive;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HistorialBillingMasive
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
     * @param  \App\Events\CheckHistorialBillingMasive  $event
     * @return void
     */
    public function handle(CheckHistorialBillingMasive $event)
    {
        //
    }
}
