<?php

namespace App\Listeners;

use App\Events\SendMail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
class Email
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
     * @param  SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
            $event = $event->email;
            $user  = User::whereId($event['user_id'])->first();
            Mail::send('mail.mailjet',['user'=>$user,'event'=>$event], function($message) use ($user, $event) {
            $message->to($user->email);
            $message->subject('Fashion app');
        });
    }
}
