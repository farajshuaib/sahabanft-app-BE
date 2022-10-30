<?php

namespace App\Notifications;

use App\Http\Resources\NftResource;
use App\Models\Nft;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class UserUpdateNftPriceNotification extends Notification
{
    use Queueable;

    private User $user;
    private Nft $nft;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nft, $user)
    {
        $this->nft = $nft;
        $this->user = $user;
    }

    public function via(mixed $notifiable): array
    {
        return ['database', FcmChannel::class];
    }


    public function toFcm($notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setData(['data' => $this->nft])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('NFT price updated')
                ->setBody($this->user->username . 'has update NFT price.')
            )
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')));
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => 'NFT price updated',
            'message' => $this->user->username . 'has update NFT price.',
            'nft' => NftResource::make($this->nft)
        ];
    }
}