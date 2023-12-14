<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Facades\Request;

class ParticipantService
{

  /**
   * A service for the creation of participants
   * 
   * @param $data
   * @return Participant|boolean
   */
  public function createParticipant($data)
  {
    $ParticipantExists = $this->checkParticipantByEmailOrPhoneNumber($data);

    if ($ParticipantExists) {
      return false;
    }

    $participant = new Participant();

    $participant->name = $data['name'];
    $participant->email = $data['email'];
    $participant->description = $data['description'];
    $participant->phone_number = $data['phone_number'];
    $participant->degree = $data['degree'];
    $participant->save();

    return $participant;
  }




  /**
   * Checking if participant already exists by the Email or Phone Number
   *  
   * @param $data
   * @return boolean
   */
  public function checkParticipantByEmailOrPhoneNumber($data)
  {
    $participant = Participant::where('email', $data['email'])
      ->orWhere('phone_number', $data['phone_number'])
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
