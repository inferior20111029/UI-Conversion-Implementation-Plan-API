<?php

namespace App\Listeners;

use App\Events\SendProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Support\EzPlus\Email;

class SendEmailNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 1;

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
    public function handle(SendProcessed $event): void
    {
        if (empty($event->emailParameter)) {
            return;
        }

        Email::send($event->emailParameter);
    }
}
