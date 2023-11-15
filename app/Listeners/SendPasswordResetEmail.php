<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordResetEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ResetPasswordRequested $event)
    {
        $resetLink = $event->resetLink;

        Mail::to($event->user->email)->send(new ResetPasswordEmail($resetLink));
    }
}
