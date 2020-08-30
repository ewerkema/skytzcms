<?php

namespace App\Listeners;

use App\Services\EmailLoggingService;
use Illuminate\Mail\Events\MessageSending;

class EmailListener
{
    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        app()->make(EmailLoggingService::class)->log($event->message);
    }
}
