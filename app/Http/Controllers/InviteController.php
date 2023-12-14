<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invites;
use App\Models\Participant;
use App\Notifications\sendInvite;
use App\Services\InviteService;
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

  public function __construct(ParticipantService $participantService, InviteService $inviteService)
  {
    $this->participantService = $participantService;
    $this->inviteService = $inviteService;
  }

  /**
   * Store information about an invited participant to a certain event
   * 
   * @param Request $request
   * @param int $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request, int $id)
  {
    try {
      $validator = Validator::make($request->all(), [
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

          $exists = $this->participantService->checkParticipantByEmailOrPhoneNumber($data);
          if ($exists) {
            $participant = Participant::where('email', $data['email'])->orWhere('phone_number', $data['phone_number'])->first();
          } else {
            $participant = $this->participantService->createParticipant($data);
            if (!$participant) {
              return redirect()->back()->with('warning', 'Algo correu mal no acto de criacao do participante.');
            }
          }

          $qrCodeName = base64_encode($id . $participant->id);
          $alreadyInvited = $this->inviteService->checkIfInviteExists($id, 2);

          $invite = new Invites();
          $invite->participant_id = $participant->id;
          $invite->event_id = $id;
          $invite->participant_type_id = $data['type'];
          $invite->qr_url = $qrCodeName;
          $participantEmail = $participant->email;

          $generateCodeResponse = $this->generateQrcode($invite->qr_url, $invite);

          if ($generateCodeResponse == 0) {
            return redirect()->back()->with('error', 'Algo de errado nao esta certo!');
          } else {
            Notification::route('mail', $participantEmail)->notify(new sendInvite(
              $invite->event_id,
              $invite->participant_id,
              $invite->qr_url
            ));

            $invite->save();
            return redirect()->back()->with('Success', 'O participante ' . $invite->name . ' Foi convidado');
          }
        } catch (Exception $e) {
          $errorMessage = $e->getMessage();
          dd($errorMessage);

          return redirect()->back()->with('error', $errorMessage);
        }
      }

      $qrCode = $this->generateQrcode($id, $data['participant']);

      if (!$qrCode) {
        return redirect()->back()->with('error', 'Tente convidar o participante novamente.')->withInput();
      }

      
      $invite = $this->inviteService->createInvite($data['participant'], $id, $data['participant_type_id'], $qrCode);

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
  public function show($encryptedevent, $encryptedparticipant)
  {
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);
    $rs = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])
      ->update(['status' => 'Confirmada']);

    $successMessage = session('success');


    $event = Event::find($eventId);
    $participant = Participant::find($participantId);

    // dd(session());

    return response(view('confirmPresence', compact('successMessage', 'event', 'participant', 'encryptedevent', 'encryptedparticipant'))->with('success', 'A sua presenca foi confirmada no evento ' . $event->name));
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request, $eventId, $participantId)
  {
    $user = Auth::user();
    $event = Event::find($eventId);
    $participant = Participant::find($participantId);
    $invite = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])->first();

    return response(view("delete", compact('user', 'event', 'invite', 'participant')));
  }
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "event" => ['required', 'numeric'],
        "participant" => ['required', 'numeric'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->with('error', 'Something went wrong!');
      }
    } catch (Exception $e) {
      throw $e;
    }

    $data = $request->all();
    $invite = Invites::where([['event_id', $data['event']], ['participant_id', $data['participant']]])->first();


    if (!$invite) {
      return redirect()->back()->with('error', 'Esse participante naoa faz parte da lista do evento.');
    }

    Invites::where([['event_id', $data['event']], ['participant_id', $data['participant']]])->delete();
    return redirect(route('event', $data['event']))->with('success', 'O participante foi removido com sucesso!');
  }

  public function confirmEntrance($encryptedevent, $encryptedparticipant)
  {
    $user = Auth::user();
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);
    $event = Event::find($eventId);
    $participant = Participant::find($participantId);
    $invite = Invites::where([['event_id', $eventId], ['participant_id', $participantId]])->first();


    return response(view('confirmEntrance', compact('user', 'event', 'encryptedevent', 'encryptedparticipant', 'participant', 'invite')));
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
      'confirmPresenceShow',
      ['encryptedevent' => $encodedEvent, 'encryptedparticipant' => $encodedParticipant]
    ));
    $qrCodePath = storage_path('app/public/qrcodes/' . $name . '.svg');
    $res = file_put_contents($qrCodePath, $qrCode);

    if (!$res) {
      return false;
    }
    return $name;
  }

  public function removerParticipante(Request $request)
  {
    try {
      $participantEvent = Invites::where([['event_id', $request->eventId], ['participant_id', $request->participantId]])->delete();

      return redirect()->back()->with('success', 'Participante removido com sucesso!');
    } catch (Exception $e) {
    }
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
