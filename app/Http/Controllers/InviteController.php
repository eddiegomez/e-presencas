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

          $qrCode = $this->generateQrcode($data['id'], $participant->id);

          if (!$qrCode) {
            return redirect()->back()->with('error', 'Tente convidar o participante novamente.')->withInput();
          }

          $sendInviteNotification = new sendInvite(
            $data['id'],
            $participant->id,
            $qrCode
          );

          $sendEmail = $this->notificationService->sendEmail($participant['email'], $sendInviteNotification);

          if (!$sendEmail) {
            return redirect()->back()->with('error', 'Ocorreu algum um erro no envio do convite via email, tente novamente.')->withInput();
          }

          // Notification::route('mail', $participant->email)->notify($sendInviteNotification);

          $invite = $this->inviteService->createInvite($data['participant'], $data['id'], $data['type'], $qrCode);
          return redirect()->back()->with('success', 'O participante foi convidado com sucesso!');
        } catch (Exception $e) {
          $errorMessage = $e->getMessage();

          return redirect()->back()->with('error', $errorMessage);
        }
      }

      $participant = $this->participantService->getParticipantById($data['participant']);

      $qrCode = $this->generateQrcode($data['id'], $data['participant']);

      if (!$qrCode) {
        return redirect()->back()->with('error', 'Tente convidar o participante novamente.')->withInput();
      }

      $sendInviteNotification = new sendInvite(
        $data['id'],
        $data['participant'],
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


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function confirmPresence($encryptedevent, $encryptedparticipant)
  {
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);
    $rs = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])
      ->update(['status' => 'Confirmada']);

    $successMessage = session('success');


    $event = Event::find($eventId);
    $participant = Participant::find($participantId);



    return response(view('confirmPresence', compact('successMessage', 'event', 'participant', 'encryptedevent', 'encryptedparticipant'))->with('success', 'A sua presenca foi confirmada no evento ' . $event->name));
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
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);
    $event = Event::find($eventId);
    $participant = Participant::find($participantId);
    $invite = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])->first();


    return response(view('confirmEntrance', compact('event', 'encryptedevent', 'encryptedparticipant', 'participant', 'invite')));
  }

  public function confirmEntrancePost($encryptedevent, $encryptedparticipant)
  {
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);

    $event = Event::where("id", $eventId)->first();


    $rs = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])
      ->update(['status' => 'Presente']);


    return redirect()->back()->with('success', 'A presenca do participante foi actualizada com sucesso!');
  }


  private function generateQrcode(int $eventId,  int $participantId): bool|string
  {
    $encodedEvent = base64_encode($eventId);
    $encodedParticipant = base64_encode($participantId);
    $name = $encodedEvent . $encodedParticipant;

    $qrCode = QrCode::format('svg')->size(100)->generate(route(
      'invite.confirmPresence',
      ['encryptedevent' => $encodedEvent, 'encryptedparticipant' => $encodedParticipant]
    ));
    $qrCodePath = storage_path('app/public/qrcodes/' . $name . '.svg');
    $res = file_put_contents($qrCodePath, $qrCode);

    if (!$res) {
      return false;
    }
    return $name;
  }

  // public function confirmPresence($encryptedevent, $encryptedparticipant)
  // {
  //   // return redirect()->route('event', 1)->with('message', 'IT WORKS!');
  //   $eventId = base64_decode($encryptedevent);
  //   $participantId = base64_decode($encryptedparticipant);


  //   $invite = Invites::where(["event_id" => $eventId, "participant_id" => $participantId])->first();
  //   $event = Event::find($eventId);
  //   // dd($event->name);

  //   $updateStatus = DB::table('participant_event')
  //     ->where('event_id', $eventId)
  //     ->where('participant_id', $participantId)
  //     ->update([
  //       'status' => 'Confirmada',
  //       'updated_at' => now()
  //     ]);

  //   // dd($updateStatus);

  //   return redirect()->route('confirmPresenceShow', ['encryptedevent' => $encryptedevent, 'encryptedparticipant' => $encryptedparticipant])->with('success', 'A sua presenca foi confirmada no evento ' . $event->name);
  // }
}
