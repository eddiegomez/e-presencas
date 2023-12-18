<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\EmailVerification;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class StaffService
{

  public function create(
    string $name,
    string $email,
    string $phone,
    int $organization
  ) {
    $staffExists = User::where('email', $email)->orWhere('phone', $phone)->first();

    if ($staffExists) {
      throw new Exception('Um usuario registado com o mesmo email ou numero de telefone!');
    }

    $organization = Organization::find($organization);

    if (!$organization) {
      throw new Exception('Não encontramos a organização a qual se refere!');
    }

    $staff = new User();
    $staff->name = $name;
    $staff->email = $email;
    $staff->phone = $phone;
    $staff->password = Hash::make($organization->name . '@1234');
    $staff->save();

    Notification::route('mail', $staff->email)->notify(new EmailVerification(
      $staff->id,
      $staff->email,
      $staff->name
    ));
  }
}
