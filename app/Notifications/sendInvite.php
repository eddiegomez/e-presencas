<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\HtmlString;

class sendInvite extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public $event;
  public $participant;
  public $qr_url;

  public function __construct($event, $participant)
  {
    $this->event = $event;
    $this->participant = $participant;
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
    $event = $this->event;
    $participant = $this->participant;

    // Generate base64 QR Code
    $qrCodeRaw = QrCode::format('png')
      ->size(180)
      ->style('round')
      ->eye('circle')
      ->color(0, 0, 0)
      ->generate('https://assiduidade.inage.gov.mz/showBusinessCard/' . base64_encode($participant->email));

    $qrCodeData = base64_encode($qrCodeRaw);

    // HTML block: centered, downloadable QR code
    $qrCodeImgTag = '
    <div style="text-align: center; margin-top: 20px;">
        <p><strong>Guarde o código abaixo para garantir sua entrada no evento:</strong></p>
        <a href="data:image/png;base64,' . $qrCodeData . '" download="qrcode.png">
            <img src="data:image/png;base64,' . $qrCodeData . '" alt="QR Code" style="margin-top: 10px; max-width: 200px; height: auto;" />
            <p style="font-size: 12px; color: #888;">Clique no código para baixar</p>
        </a>
    </div>';

    return (new MailMessage)
      ->subject("Convite para Participar do(a) " . $event->name)
      ->greeting("Olá " . $participant->name)
      ->line("Temos o prazer de convidá-lo(a) para participar do(a) " . $event->name . ".")
      ->line("**Detalhes do Evento:**")
      ->line("**Data de início:** " . $event->start_date)
      ->line("**Data de término:** " . $event->end_date)
      ->line("**Horário:** " . date("H:i", strtotime($event->start_time)) . " - " . date("H:i", strtotime($event->end_time)))
      ->line("Para confirmar sua presença, clique no botão abaixo.")
      ->action("Confirmar", route("invite.acceptInvite", [
        "encryptedevent" => base64_encode($event->id),
        "encryptedparticipant" => base64_encode($participant->id)
      ]))
      ->line(new HtmlString($qrCodeImgTag)); // QR code HTML
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
