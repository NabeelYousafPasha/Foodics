<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IngredientThresholdListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // here we will notify about threshold of ingredient, if email is sent we will skip
        // until stock of ingredient resets the "available_quantity" column above
        // 50% (or whatever is the criteria) 
    }
}
