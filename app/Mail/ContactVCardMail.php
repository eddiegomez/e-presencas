<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactVCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vCard;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vCard, $name)
    {
        $this->vCard = $vCard;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact_vcard')
                    ->subject('Contact Information')
                    ->attachData($this->vCard, "{$this->name}.vcf", [
                        'mime' => 'text/vcard',
                    ]);
    }
}
