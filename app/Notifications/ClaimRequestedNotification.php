<?php

namespace PRStats\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PRStats\Models\Claim;

class ClaimRequestedNotification extends Notification implements ShouldQueue
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
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Player Claim Request')
            ->line('To claim your player profile "'.$this->claim->player->name.'", you need to temporarily change your clan tag to: '.$this->claim->code)
            ->action('View instructions', route('claim.show', $this->claim->uuid))
            ->line('See you at the battlefield!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
