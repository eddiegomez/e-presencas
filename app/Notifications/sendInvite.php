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
  public $eventId;
  public $participantId;

  public function __construct($eventId, $participantId)
  {
    $this->eventId = $eventId;
    $this->participantId = $participantId;
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
    return (new MailMessage)
      ->line('The introduction to the notification.')
      ->line(QrCode::generate('Make me into a QrCode!'))
      ->action('Notification Action', route('confirmPresence', ["encryptedevent" => base64_encode($this->eventId), "encryptedparticipant" => base64_encode($this->participantId)]))
      ->line('Thank you for using our application!');
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
