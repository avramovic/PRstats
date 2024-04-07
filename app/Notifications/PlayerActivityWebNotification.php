<?php

namespace PRStats\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use PRStats\Models\Player;
use PRStats\Models\Round;

class PlayerActivityWebNotification extends Notification
{
    use Queueable;

    /**
     * @var Round
     */
    private $match;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Round $match)
    {
        $this->match = $match;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OneSignalChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param Player $notifiable
     */
    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject(vsprintf('%s is playing PR', [
                $notifiable->name,
            ]))
            ->setBody(vsprintf('%s is now playing %s on %s. Click to see the details.', [
                $notifiable->full_name,
                $this->match->map->name,
                $this->match->server->name,
            ]))
            ->setUrl($notifiable->getLink())
            ->setIcon(('https://prstats.tk/img/logo.png'));
    }
}
