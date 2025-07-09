<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Email;

class ShareVCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $qrCodePath;

    public function __construct($participant, $qrCodeRaw)
    {
        $this->participant = $participant;

        // Cria arquivo temporário
        $filename = 'qrcode_' . Str::random(10) . '.png';
        $path = storage_path('app/public/' . $filename);

        File::put($path, $qrCodeRaw);

        $this->qrCodePath = $path;
    }

    public function build()
    {
        return $this->subject("Partilha do Cartão de Visita Digital")
            ->view('emails.share_vcard')
            ->with([
                'participant' => $this->participant,
            ])
            ->withSymfonyMessage(function (Email $message) {
                $message->embedFromPath($this->qrCodePath, 'qrcodeimg');
            });
    }
}
