<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
{
  use Queueable;

  public $email;
  public $name;
  public $id;
  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($id, $email, $name)
  {
    $this->id = $id;
    $this->email = $email;
    $this->name = $name;
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
      ->subject("Verificação de Email")
      ->greeting('Saudações ' . $this->name . '!')
      ->line('Notificamos-lhe que o seu email foi usado para o registro, na plataforma e-presencas como Protocolo.')
      ->line('Clique no botao abaixo para confirmar o seu email!')
      ->action('Confirmar o meu email!', route('protocolo.confirmation', base64_encode($this->id)))
      ->line('Obrigado pela sua atencao!');
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
