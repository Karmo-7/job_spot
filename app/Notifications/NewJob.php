<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJob extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

     private $id_job;
     private $user_create;

     public function __construct($id_job, $user_create)
    {
        $this->id_job=$id_job;
        $this->user_create=$user_create;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'job_id'=>$this->id_job,
            'user_create'=>$this->user_create
        ];
    }
}
