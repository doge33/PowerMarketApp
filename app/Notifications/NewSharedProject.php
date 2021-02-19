<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSharedProject extends Notification
{
    use Queueable;
<<<<<<< HEAD

=======
>>>>>>> ec5a099c2dfe42ba07dca9436099b612302980b6
    public $user; //global variable user
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $cluster)
    {
        $this->user = $user;  //pass the sender in which becomes the publically available variable $user
        $this->cluster = $cluster;
    }
<<<<<<< HEAD

=======
>>>>>>> ec5a099c2dfe42ba07dca9436099b612302980b6
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
<<<<<<< HEAD

=======
>>>>>>> ec5a099c2dfe42ba07dca9436099b612302980b6
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
<<<<<<< HEAD

=======
>>>>>>> ec5a099c2dfe42ba07dca9436099b612302980b6
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
             'sharer_name' => $this->user->name, //referencing the public variable $user
             'cluster' => $this->cluster->id,
             'project_name' => $this->cluster->name,
        ];
    }
}
