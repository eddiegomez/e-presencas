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
    $event = $this->event;
    $participant = $this->participant;

    return (new MailMessage)
      ->subject("Convite para Participar do(a) " . $event->name)
      ->greeting("Olá " . $participant->name)
      ->line("Temos o prazer de convidá-lo(a) para participar do(a), " . $event->name . ".")
      ->line("**Detalhes do Evento:**")
      ->line("**Data de início:** " . $event->start_date)
      ->line("**Data de término:** " . $event->end_date)
      ->line("**Horário:** " . date("H:i", strtotime($event->start_time)) . " - " . date("H:i", strtotime($event->end_time)))
      ->line("Para confirmar sua presença, clique no botão abaixo.")
      ->action("Confirmar", route("invite.acceptInvite", [
        "encryptedevent" => base64_encode($event->id),
        "encryptedparticipant" => base64_encode($participant->id)
      ]));
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
