<?php

namespace App\Observers;

use App\Models\Venue;

class VenueObserver
{
    /**
     * Handle the Venue "created" event.
     */
    public function created(Venue $venue): void
    {
        //
    }

    /**
     * Handle the Venue "updated" event.
     */
    public function updated(Venue $venue): void
    {
        //
    }

    /**
     * Handle the Venue "deleted" event.
     */
    public function deleted(Venue $venue): void
    {
        $venue->update(['deleted_by_venue' => auth()->id()]);
    }

    /**
     * Handle the Venue "restored" event.
     */
    public function restored(Venue $venue): void
    {
        //
    }

    /**
     * Handle the Venue "force deleted" event.
     */
    public function forceDeleted(Venue $venue): void
    {
        //
    }
}
