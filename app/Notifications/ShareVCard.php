<?php
namespace App\Notifications;
use Illuminate\Notifications\Notification;
use App\Mail\ShareVCardMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShareVCard extends Notification
{
    public $participant;

    public function __construct($participant)
    {
        $this->participant = $participant;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $qrCodeRaw = QrCode::format('png')
            ->size(180)
            ->style('round')
            ->eye('circle')
            ->color(0, 0, 0)
            ->generate('https://assiduidade.inage.gov.mz/showBusinessCard/' . base64_encode($this->participant->email));

        return new ShareVCardMail($this->participant, $qrCodeRaw);
    }
}
