<?php

namespace App\Listeners;

use App\Events\EventCreationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\EventNotification;
use App\Schedule;
use App\User;
use Notification;

class EventCreationListener
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
    public function handle(EventCreationEvent $event)
    {
            $schedule = Schedule::with(['equipment.product',])->find($event->users->schedule_id)->first();
            $user = User::whereId($event->users->user_id)->first();
            $title = __("site.eventTitle");
            $message = $schedule->equipment->product->name;
            $device_token = $user->device_token;
            $detail = [
                            "schedule_id" => $event->users->schedule_id,
                            "user_id" =>    $event->users->user_id,
                            "module_type" => $event->users->module_type,
                            "type" => $event->users->type,
                            "product_id" => $schedule->equipment->id,
                            "approval_status" => $schedule->engineer_status
                       ];
            $event->users['product_id'] = $schedule->equipment->product->id;
            Notification::send($user,new EventNotification($title, $message,$device_token, $detail));
            
            if($user->user_type == 3 )
            {
                $spectator_user = User::whereUserType(4)->whereActive(1)->where('device_token','!=',NULL)->get();
                foreach($spectator_user as $spectator)
                {
                    $details = [
                            "schedule_id" => $event->users->schedule_id,
                            "user_id" =>    $spectator->id,
                            "module_type" => $event->users->module_type,
                            "type" => $event->users->type,
                            "product_id" => $schedule->equipment->id,
                            "approval_status" => $schedule->engineer_status
                       ];
                    
                    Notification::send($spectator,new EventNotification($title, $message,$spectator->device_token, $details));
                }
            }
    }
}
