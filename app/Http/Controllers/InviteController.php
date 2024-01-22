<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invites;
use App\Models\Participant;
use App\Notifications\sendInvite;
use App\Services\InviteService;
use App\Services\NotificationService;
use App\Services\ParticipantService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InviteController extends Controller
{


  protected $participantService;
  protected $inviteService;

  protected $notificationService;

  public function __construct(ParticipantService $participantService, InviteService $inviteService, NotificationService $notificationService)
  {
    $this->participantService = $participantService;
    $this->inviteService = $inviteService;
    $this->notificationService = $notificationService;
  }

  /**
   * Store information about an invited participant to a certain event
   * 
   * @param Request $request
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer'],
        'participant' => ['required'],
        'type' => ['required', 'numeric']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->all();

      if ($data['participant'] == 'new') {
        try {
          $validator2 = Validator::make($request->all(), [
            'name' => ['required', 'string', 'regex:/^[^\d]+$/'],
            'email' => ['required', 'email'],
            'degree' => ['required', 'string'],
            'phone_number' => ['required', 'numeric'],
            'description' => ['required', 'string']
          ]);

          if ($validator2->fails()) {
            return redirect()->back()->withErrors($validator2)->withInput();
          }

          $exists = $this->participantService->checkParticipantByEmailOrPhoneNumber($data['email'], $data['phone_number']);
          if ($exists) {
            $participant = Participant::where('email', $data['email'])->orWhere('phone_number', $data['phone_number'])->first();
          } else {
            $participant = $this->participantService->createParticipant(
              $data['name'],
              $data['email'],
              $data['description'],
              $data['phone_number'],
              $data['degree']
            );
            if (!$participant) {
              return redirect()->back()->with('warning', 'Algo correu mal no acto de criacao do participante.');
            }
          }

          // dd($participant);

          $qrCode = $this->generateQrcode($data['id'], $participant->id);

          if (!$qrCode) {
            return redirect()->back()->with('error', 'Tente convidar o participante novamente.')->withInput();
          }

          $event = Event::find($data['id']);

          $sendInviteNotification = new sendInvite(
            $event,
            $participant,
            $qrCode
          );

          $sendEmail = $this->notificationService->sendEmail($participant['email'], $sendInviteNotification);

          if (!$sendEmail) {
            return redirect()->back()->with('error', 'Ocorreu algum um erro no envio do convite via email, tente novamente.')->withInput();
          }

          // Notification::route('mail', $participant->email)->notify($sendInviteNotification);
          $invite = $this->inviteService->createInvite($participant->id, $data['id'], $data['type'], $qrCode);
          return redirect()->back()->with('success', 'O participante foi convidado com sucesso!');
        } catch (Exception $e) {
          $errorMessage = $e->getMessage();

          return redirect()->back()->with('error', $errorMessage);
        }
      }

      $participant = $this->participantService->getParticipantById($data['participant']);
      $event = Event::find($data['id']);
      $qrCode = $this->generateQrcode($data['id'], $data['participant']);

      if (!$qrCode) {
        return redirect()->back()->with('error', 'Tente convidar o participante novamente.')->withInput();
      }

      $sendInviteNotification = new sendInvite(
        $event,
        $participant,
        $qrCode
      );

      $sendEmail = $this->notificationService->sendEmail($participant->email, $sendInviteNotification);

      if (!$sendEmail) {
        return redirect()->back()->with('error', 'Ocorreu algum um erro no envio do convite via email, tente novamente.')->withInput();
      }

      $invite = $this->inviteService->createInvite($data['participant'], $data['id'], $data['type'], $qrCode);

      return redirect()->back()->with('success', 'O participante foi convidado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }



  public function acceptInvite($encryptedevent, $encryptedparticipant)
  {
    try {
      $invite = $this->inviteService->acceptInvite($encryptedevent, $encryptedparticipant);

      return view('confirmPresence', compact('invite'))->with('success', 'A sua presenca foi confirmada no evento ' . $invite->event->name);
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
      // dd(session()->all());
      return view('error.404', ['error' => $errorMessage]);
    }
  }

  /**
   * Update the participant type
   *
   * @param int $eventId
   * @param int $participantId
   * @param Request $request
   * 
   * @return RedirectResponse 
   */

  public function update(int $eventId, Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'participant' => ['required', 'integer'],
        'type' => ['required', 'integer']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $this->inviteService->updateParticipant($eventId, $request->participant, $request->type);

      return redirect()->back()->with('success', 'O participante foi actualizado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  /**
   * Remove the specified Invite from storage.
   * @param Request $request 
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'event' => ['required', 'numeric'],
        'participant' => ['required', 'numeric']
      ]);


      if ($validator->fails()) {
        return redirect()->back()->with('error', 'Algo ocorreu mal na validação dos campos, tente novamente!');
      }

      $this->inviteService->deleteInvite($request->event, $request->participant);

      return redirect()->back()->with('success', 'O participante foi eliminado da lista dos convidados com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function confirmEntrance($encryptedevent, $encryptedparticipant)
  {
    try {

      $eventId = base64_decode($encryptedevent);
      $participantId = base64_decode($encryptedparticipant);

      $invite = $this->inviteService->getInviteByCompositeKey($eventId, $participantId);
      $event = $invite->event;
      $participant = $invite->participant;

      return view('confirmEntrance', compact('event', 'encryptedevent', 'encryptedparticipant', 'participant', 'invite'));
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return view('error.404', ['error' => $errorMessage]);
    }
  }

  public function confirmEntrancePost($encryptedEvent, $encryptedParticipant)
  {
    try {
      $updatedInvite = $this->inviteService->confirmEntrance($encryptedEvent, $encryptedParticipant);
      return redirect()->route("event.show", $updatedInvite->event_id)->with('success', 'A presenca do participante foi actualizada com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return view('error.404', ['error' => $errorMessage]);
    }
  }

  public function rejectInvite(string $encodedEvent, string $encodedParticipant)
  {
    try {
      $invite = $this->inviteService->rejectInvite($encodedEvent, $encodedParticipant);

      return view('rejectInvite', compact('invite'))->with('warning', 'Rejeitou o convitr com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return view('error.404', ['error' => $errorMessage]);
    }
  }


  private function generateQrcode(int $eventId,  int $participantId): bool|string
  {
    $encodedEvent = base64_encode($eventId);
    $encodedParticipant = base64_encode($participantId);
    $name = $encodedEvent . $encodedParticipant;

    $qrCode = QrCode::format('png')->size(100)->generate(route(
      'participant.entrance',
      ['encryptedevent' => $encodedEvent, 'encryptedparticipant' => $encodedParticipant]
    ));
    $qrCodePath = storage_path('app/public/qrcodes/' . $name . '.png');
    $res = file_put_contents($qrCodePath, $qrCode);

    if (!$res) {
      return false;
    }
    return $name;
  }
}
