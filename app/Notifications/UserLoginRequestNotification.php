<?php

namespace PRStats\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PRStats\Models\User;

class UserLoginRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var bool
     */
    private $newUser = false;

    public function __construct(bool $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param User $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = (new MailMessage)
            ->subject($this->newUser ? 'New User Created' : 'User Login Request')
            ->line('You can click the button below to log in to PRstats.tk. The link will be valid for 30 minutes.');

        $msg->action('Log In', $notifiable->getLoginLink())
            ->line('See you at the battlefield!');

        return $msg;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
