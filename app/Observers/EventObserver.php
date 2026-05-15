<?php

namespace App\Observers;

use App\Models\Event;
use Illuminate\Support\Facades\Mail;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        Mail::html("<h1>Evento creado</h1><p>Se ha creado el evento $event->event_name</p>",
            function ($message) {
            $message->to("crisechevrri@gmail.com")
                ->subject('Nuevo evento creado en el sistema');
        });
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
