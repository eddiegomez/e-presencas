<?php

namespace App\Services;

use App\Models\Invites;
use App\Models\Participant;
use Exception;

class InviteService
{

  protected $participantService;
  protected $eventService;

  public function __construct(ParticipantService $participantService, EventService $eventService)
  {
    $this->participantService = $participantService;
    $this->eventService = $eventService;
  }

  /**
   * Check if the Participant has been invited
   * @param int $eventId
   * @param int $participantId
   * @return boolean
   */

  public function checkIfInviteExists(
    int $eventId,
    int $participantId
  ) {
    $existingInvite = Invites::where('participant_id', $participantId)->where('event_id', $eventId)->first();

    if ($existingInvite) {
      return true;
    }

    return false;
  }

  /**
   * The function that creates the Invite
   * @param int $participantId
   * @param int $eventId
   * @param int $participant_type_id
   * @param string $qr_url
   * 
   * @return Invites
   */

  public function createInvite(
    int $participantId,
    int $eventId,
    int $participant_type_id,
    string $qr_url
  ) {

    $participantExists = $this->participantService->getParticipantById($participantId);
    if (!$participantExists) {
      throw new Exception('O participante que tentou convidar não existe.');
    }

    $inviteAlreadyExists = $this->checkIfInviteExists($eventId, $participantId);
    if ($inviteAlreadyExists) {
      throw new Exception('Este participante já foi convidado!');
    }

    return Invites::create(
      [
        'participant_id' => $participantId,
        'event_id' => $eventId,
        'participant_type_id' => $participant_type_id,
        'qr_url' => $qr_url
      ]
    );
  }

  /**
   * Get Invite by the composite key
   * @param int $eventId
   * @param int $participantId
   * 
   * @return Invites
   */
  public function getInviteByCompositeKey(int $eventId, int $participantId)
  {
    $invite = Invites::where('event_id', $eventId)->where('participant_id', $participantId)->first();

    if (!$invite) {
      throw new Exception('Não encontramos o convite referenciado!');
    }

    return $invite;
  }


  /**
   * The function responsible for updating the participant type
   * @param int $eventId
   * @param int $participantId
   * @param int $participantType 
   * 
   * @return Participant
   */

  public function updateParticipant(int $eventId, int $participantId, int $participantType): Participant
  {
    $participant = $this->participantService->getParticipantById($participantId);
    $event = $this->eventService->getEventById($eventId);
    $invite = $this->getInviteByCompositeKey($eventId, $participantId);

    if ($invite) {
      $updateInvite = Invites::where('event_id', $eventId)->where('participant_id', $participantId)->update(['participant_type_id' => $participantType]);
      // dd($updateInvite);
      return $participant;
    } else {
      throw new Exception('Wuaaaaaaaaaaaaaaa');
    }
  }

  /**
   * Delete the Invite
   * @param int $eventId
   * @param int $participantId
   * 
   */

  public function deleteInvite(int $eventId, int $participantId): int
  {
    $invite = $this->getInviteByCompositeKey($eventId, $participantId);
    if (!$invite) {
      throw new Exception("O convite nao existe");
    }

    return Invites::where('event_id', $eventId)->where('participant_id', $participantId)->delete();
  }
}