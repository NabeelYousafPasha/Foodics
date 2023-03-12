<?php

namespace App\Listeners;

use App\Events\IngredientThresholdEvent;
use App\Mail\IngredientThresholdLevelReachedMail;
use App\Models\IngredientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
     * @param IngredientThresholdEvent $ingredientThresholdEvent
     */
    public function handle(IngredientThresholdEvent $ingredientThresholdEvent)
    {
        // here we will notify about threshold of ingredient, if email is sent we will skip
        // until stock of ingredient resets the "available_quantity" column above
        // 50% (or whatever is the criteria)

        $ingredient = $ingredientThresholdEvent->ingredient;

        /*
         * @IMPORTANT - hasThresholdAlreadyNotified()
         *
         * We will always reset this to NULL whenever new stock of ingredients are added
         * and it updates available_quantity to be more than threshold_level/unit (for example 50%)
         * so that NULL means we need to notify again next time, whenever stock gets equal or below threshold
         * */

        if ($ingredient->hasIngredientThresholdLevelAchieved()
            &&
            ! $ingredient->hasThresholdAlreadyNotified()
        ) {
            Mail::to('nabeelyousafpasha@gmail.com')
                ->queue(new IngredientThresholdLevelReachedMail($ingredient));

            $ingredient->update([
                'last_threshold_notified_at' => now(),
            ]);

            IngredientNotification::create([
                'ingredient_id' => $ingredient->id,
                'mailable' => IngredientThresholdLevelReachedMail::class,
                'is_dispatched_successfully' => 0,
            ]);

        }

        $ingredient->refresh();
    }
}
