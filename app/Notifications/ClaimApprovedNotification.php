<?php

namespace PRStats\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PRStats\Models\Claim;

class ClaimApprovedNotification extends Notification
{
    use Queueable;

    /**
     * @var Claim
     */
    private $claim;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->claim = Claim::withTrashed()->findOrFail($id);
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
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = (new MailMessage)
            ->line('Hello '.$notifiable->name)
            ->line('Your player profile '.$this->claim->player->name.' was successfully claimed on PRstats.tk.');

        if (!empty($this->claim->old_clan_tag)) {
            $msg->line('You can now revert your clan tag in game to: '.$this->claim->old_clan_tag);
        } else {
            $msg->line('You can now remove your clan tag in game.');
        }

        $msg->action('See player profile', $this->claim->player->getLink())
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
