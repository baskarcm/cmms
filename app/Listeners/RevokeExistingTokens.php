<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Laravel\Passport\Token;

class RevokeExistingTokens
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
        Token::where('user_id', $event->userId)
            ->where('id', '<>', $event->tokenId)
            ->update(['revoked' => true]);
        
    }
}
