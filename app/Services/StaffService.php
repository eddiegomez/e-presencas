<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\EmailVerification;
use Exception;
use Illuminate\Support\Facades\Auth;
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

    $organization = Organization::find($organization_id);

    if ($staffExists) {
      if ($staffExists->trashed()) {
        $staffExists->name = $name;
        $staffExists->email = $email;
        $staffExists->phone = $phone;
        $staffExists->password = Hash::make($organization->name . '@1234');
        $staffExists->restore();
        return $staffExists;
      } else {
        throw new Exception('Um usuario registado com o mesmo email ou número de telefone!');
      }
    }

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
    $staff->password = Hash::make($staff->organization->name . '@1234');
    $staff->email_verified_at = null;
    $updated = $staff->update();

    if (!$updated) {
      throw new Exception('Houve algum erro durante a actualização de dados tente novamente!');
    }

    Notification::route('mail', $staff->email)->notify(new EmailVerification(
      $staff->id,
      $staff->email,
      $staff->name
    ));

    return $staff;
  }

  public function delete(
    int $id
  ) {
    $this->getUserById($id);
    $staff = User::find($id);


    return $staff->delete();
  }

  /**
   * Confirm the staff registration
   * @param string $encryptedId
   * @param string $defaultPassword
   * @param string $newPassword
   * @throws \Exception
   * @return User
   */
  public function confirmRegistration(
    string $encryptedId,
    string $defaultPassword,
    string $newPassword
  ) {

    $id = base64_decode($encryptedId);
    $staff = $this->getUserById($id);

    if ($staff->hasVerifiedEmail()) {
      throw new Exception('O email já foi verificado.');
    }

    if (Hash::check($defaultPassword, $staff->password)) {
      $staff->password = Hash::make($newPassword);
      $staff->markEmailAsVerified();
      $staff->save();

      return $staff;
    }

    throw new Exception('O password predefindo não está correcto!');
  }
}
