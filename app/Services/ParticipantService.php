<?php

namespace App\Services;

use App\Models\Participant;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

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
    string $last_name,
    string $email,
    string $description,
    string $phoneNumber,
    string $degree
    //string $profile_url
  ) {
    $ParticipantExists = $this->checkParticipantByEmailOrPhoneNumber($email, $phoneNumber);

    if ($ParticipantExists) {
      throw new Exception("Participante com este email ou numero de telefone ja existe.");
    }

    $participant = new Participant();

    $participant->name = $name;
    $participant->last_name = $last_name;
    $participant->email = $email;
    $participant->description = $description;
    $participant->phone_number = $phoneNumber;
    $participant->degree = $degree;
    $participant->profile_url = "";
    $participant->organization_id = Auth::user()->organization_id;
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
    $participant = Participant::leftjoin('invites', 'invites.participant_id', 'participants.id')
      ->leftjoin('participant_type', 'participant_type.id', 'invites.participant_type_id')
      ->select('participants.*', 'participant_type.name as participant_type')
      ->where('participants.id', $id)
      ->first();

    if ($participant) {
      return $participant;
    }

    return false;
  }

  /**
   * Update participant information in database
   * @param array|mixed $data
   * @param int $id
   * 
   */

  public function update(array $data, int $id)
  {
    $participant = $this->getParticipantById($id);

    $this->checkUniqueness($data['email'], $data['phone_number'], $id);

    $participant->update($data);

    return $participant;
  }

  /**
   * Check if the participant update data is unque
   * @param string $email
   * @param string $phoneNumber
   * @param int $id
   * @throws Exception
   * @return void
   */
  protected function checkUniqueness(string $email, string $phoneNumber, int $id)
  {
    // Perform the uniqueness check
    $existingParticipant = Participant::where(function ($query) use ($email, $phoneNumber, $id) {
      $query->where(function ($query) use ($email, $phoneNumber) {
        $query->where('email', $email)
          ->orWhere('phone_number', $phoneNumber);
      })
        ->when($id, function ($query) use ($id) {
          $query->where('id', '!=', $id);
        });
    })->first();

    if ($existingParticipant) {
      throw new Exception('Este email ou número de celular já esta em uso!');
    }
  }

  /**
   * Delete an existing participant
   * @param int $id
   * @throws \Exception
   * @return mixed
   */
  public function delete(int $id)
  {
    $participant = Participant::find($id);

    if (!$participant) {
      throw new Exception('O participante nao existe na nossa base de dados!');
    }

    return $participant->delete();
  }
}
