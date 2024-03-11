<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use Exception;

class NotificationService
{
  /**
   * Check if email was sent successfully
   * 
   * @param string $email
   * @param mixed $notification
   * @return bool
   */
  public function sendEmail(string $email, $notification)
  {
    try {
      // Send the email notification
      Notification::route('mail', $email)->notify($notification);
      
      // If we reach this point, the notification was sent successfully
      return true;
    } catch (Exception $e) {
      // Log or handle the exception if needed
      dd($e);
      return false;
    }
  }
}
