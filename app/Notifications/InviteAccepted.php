<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteAccepted extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */

  public $qr_path;
  public $event;

  public function __construct($qr_path, $event)
  {
    $this->qr_path = $qr_path;
    $this->event = $event;
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
    $image_path = $this->qr_path;
    return (new MailMessage)
      ->subject('Acesso ao Evento ' . $this->event->name)
      ->view("emails.qrcode", compact("image_path"));
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
