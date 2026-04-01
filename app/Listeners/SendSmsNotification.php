<?php

namespace App\Listeners;

use App\Events\SendProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Support\EzPlus\Sms;

class SendSmsNotification implements ShouldQueue
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
        if (empty($event->smsParameter)) {
            return;
        }

        Sms::send($event->smsParameter);
    }
}
