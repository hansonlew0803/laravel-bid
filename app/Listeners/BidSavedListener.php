<?php

namespace App\Listeners;

use App\Events\BidSaved;
use App\Models\User;
use App\Notifications\NotifyAllUsers;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BidSavedListener
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
     * @param  \App\Events\BidSaved  $event
     * @return void
     */
    public function handle(BidSaved $event)
    {
        $bid = $event->bid;

        User::chunk(100, function($users) {
            foreach ($users as $user) {
                $user->notify(new NotifyAllUsers());
            }
        });

        // Example: Log the bid creation
        Log::info('Bid created: ' . $bid->id);
    }
}
