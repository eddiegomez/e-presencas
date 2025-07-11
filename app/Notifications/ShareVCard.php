<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareVCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $qrCodePath;
    public $cid;

    public function __construct($participant, $qrCodePath)
    {
        $this->participant = $participant;
        $this->qrCodePath = $qrCodePath;
        $this->cid = 'qr_code_' . uniqid(); // apenas o ID, sem "cid:"
    }

    public function build()
    {
        return $this->subject("Convite para Participar do Evento")
            ->view('emails.share_vcard')
            ->with([
                'participant' => $this->participant,
                'cid' => $this->cid,
            ])
            ->attach($this->qrCodePath, [
                'as' => 'qrcode.png',
                'mime' => 'image/png',
            ])
            ->withSwiftMessage(function ($message) {
                foreach ($message->getChildren() as $child) {
                    if (method_exists($child, 'getFilename') && $child->getFilename() === 'qrcode.png') {
                        $child->setId($this->cid);
                    }
                }
            });
    }
}
