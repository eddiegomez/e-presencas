<?php

namespace App\Services;

use App\Models\Participant;
use Exception;
use Illuminate\Support\Facades\Request;

class ParticipantService
{

  /**
   * A service for the creation of participants
   * 
   * @param $name
   * @param $email
   * @param $description
   * @param $phoneNumber
   * @param $degree
   *  
   * @return Participant
   */
  public function createParticipant(
    string $name,
    string $email,
    string $description,
    string $phoneNumber,
    string $degree
  ) {
    $ParticipantExists = $this->checkParticipantByEmailOrPhoneNumber($email, $phoneNumber);

    if ($ParticipantExists) {
      throw new Exception("Participante com este email ou numero de telefone ja existe.");
    }

    $participant = new Participant();

    $participant->name = $name;
    $participant->email = $email;
    $participant->description = $description;
    $participant->phone_number = $phoneNumber;
    $participant->degree = $degree;
    $participant->save();

    return $participant;
  }




  /**
   * Checking if participant already exists by the Email or Phone Number
   *  
   * @param $email
   * @param $phoneNumber
   * @return boolean
   */
  public function checkParticipantByEmailOrPhoneNumber(string $email, string $phoneNumber)
  {
    $participant = Participant::where('email', $email)
      ->orWhere('phone_number', $phoneNumber)
      ->first();

    if ($participant) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Check if participant exists by ID
   * @param int $id
   * @return boolean|Participant
   */

  public function getParticipantById(int $id)
  {
    $participant = Participant::find($id);

    if ($participant) {
      return $participant;
    }

    return false;
  }
}
