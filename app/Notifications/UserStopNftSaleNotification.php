<?php

namespace App\Notifications;

use App\Http\Resources\NftResource;
use App\Models\Nft;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class UserStopNftSaleNotification extends Notification
{

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
        return ['database', 'mail', FcmChannel::class];
    }


    public function toFcm($notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setData(['data' => json_encode($this->nft)])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('NFT stop sale')
                ->setBody($this->user->username . 'has stop selling NFT.')
            )
            ->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')));
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->user->username . 'has stop selling NFT.')
            ->action('Preview', url('https://app.sahabanft.com.ly/nft-details/' . $this->nft->id))
            ->line('Thank you for using sahabanft app!');
    }

    public function toArray(mixed $notifiable): array
    {
        return [
            'title' => 'NFT stop sale',
            'message' => $this->user->username . 'has stop selling NFT',
            'nft' => NftResource::make($this->nft),
        ];
    }
}
