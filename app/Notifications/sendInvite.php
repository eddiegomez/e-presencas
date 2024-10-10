<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    // dd($this->participant);
    $event = $this->event;
    $participant = $this->participant;
    return (new MailMessage)
      ->subject("Convite para " . $event->name)
      ->greeting("Saudações " . $participant->name)
      ->line("Foi convidado a participar do evento " . $event->name)
      ->line("O evento ira decorrer pelas " . date("H:i", strtotime($event->start_time)) . " até às " . date("H:i", strtotime($event->end_time)) . " do dia " . $event->start_date)
      ->line("Clique no botão abaixo para confirmar sua presença.")
      ->action("Confirmar presença", route("invite.acceptInvite", ["encryptedevent" => base64_encode($event->id), "encryptedparticipant" => base64_encode($participant->id)]))
      ->line("Após confirmar a sua presença receberá o QR Code de acesso ao evento!");
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
