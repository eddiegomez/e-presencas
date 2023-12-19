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


  /**
   * Get a user by Id
   * @param int $id
   * @throws \Exception
   * @return User
   */

  public function getUserById(int $id)
  {
    $staff = User::find($id);

    if (!$staff) {
      throw new Exception('Não encontramos esse protocolo!');
    }

    return $staff;
  }

  /**
   * Create a new staff member
   * @param string $name
   * @param string $email
   * @param string $phone
   * @param int $organization_id
   * @throws \Exception
   * @return User
   */
  public function create(
    string $name,
    string $email,
    string $phone,
    int $organization_id
  ) {
    $staffExists = User::withTrashed()->where('email', $email)->orWhere('phone', $phone)->first();

    if ($staffExists) {
      if ($staffExists->trashed()) {
        $staffExists->name = $name;
        $staffExists->email = $email;
        $staffExists->phone = $phone;
        $staffExists->restore();
        return $staffExists;
      } else {
        throw new Exception('Um usuario registado com o mesmo email ou numero de telefone!');
      }
    }

    $organization = Organization::find($organization_id);

    if (!$organization) {
      throw new Exception('Não encontramos a organização a qual se refere!');
    }

    $staff = new User();
    $staff->name = $name;
    $staff->email = $email;
    $staff->phone = $phone;
    $staff->password = Hash::make($organization->name . '@1234');
    $staff->organization_id = $organization_id;
    $staff->save();

    $staff->assignRole('protocolo');

    Notification::route('mail', $staff->email)->notify(new EmailVerification(
      $staff->id,
      $staff->email,
      $staff->name
    ));

    return $staff;
  }

  /**
   * Update a certain staff member data
   * @param int $id
   * @param string $name
   * @param string $email
   * @param string $phone
   * @return User
   */
  public function update(
    int $id,
    string $name,
    string $email,
    string $phone
  ) {
    $staff = $this->getUserById($id);

    $staff->name = $name;
    $staff->email = $email;
    $staff->phone = $phone;
    $staff->email_verified_at = null;
    $staff->update();

    return $staff;
  }

  public function delete(
    int $id
  ) {
    $this->getUserById($id);
    $staff = User::find($id);


    return $staff->delete();
  }
}
