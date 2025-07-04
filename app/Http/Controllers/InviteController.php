<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invites;
use App\Models\Participant;
use App\Notifications\InviteAccepted;
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
            'last_name' => ['required', 'string', 'regex:/^[^\d]+$/'],
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
            $participant = Participant::where('id', $data['participant'])->first();
          } else {
            $participant = $this->participantService->createParticipant(
              $data['name'],
              $data['last_name'],
              $data['email'],
              $data['description'],
              $data['phone_number'],
              $data['degree']
            );
            if (!$participant) {
              return redirect()->back()->with('warning', 'Algo correu mal no acto de criacao do participante.');
            }
          }

          $event = Event::find($data['id']);

          $sendInviteNotification = new sendInvite(
            $event,
            $participant,
          );

          $sendEmail = $this->notificationService->sendEmail($participant['email'], $sendInviteNotification);

          if (!$sendEmail) {
            return redirect()->back()->with('error', 'Ocorreu algum um erro no envio do convite via email, tente novamente.')->withInput();
          }

          $invite = $this->inviteService->createInvite($participant->id, $data['id'], $data['type']);
          return redirect()->back()->with('success', 'O participante foi convidado com sucesso!');
        } catch (Exception $e) {
          $errorMessage = $e->getMessage();

          return redirect()->back()->with('error', $errorMessage);
        }
      }

      $participant = $this->participantService->getParticipantById($data['participant']);
      $event = Event::find($data['id']);

      $sendInviteNotification = new sendInvite(
        $event,
        $participant,
      );

      $sendEmail = $this->notificationService->sendEmail($participant->email, $sendInviteNotification);

      if (!$sendEmail) {
        return redirect()->back()->with('error', 'Ocorreu algum um erro no envio do convite via email, tente novamente.')->withInput();
      }

      $invite = $this->inviteService->createInvite($data['participant'], $data['id'], $data['type']);

      return redirect()->back()->with('success', 'O participante foi convidado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }



  public function acceptInvite($encryptedevent, $encryptedparticipant)
  {
    try {


      $invite = $this->inviteService->getInviteByCompositeKey(base64_decode($encryptedevent), base64_decode($encryptedparticipant));

      $this->inviteService->acceptInvite($encryptedevent, $encryptedparticipant);
      $sendAccessQrCode = new InviteAccepted($invite->qr_url, $invite->event);


      $this->notificationService->sendEmail($invite->participant->email, $sendAccessQrCode);

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

  public function confirmEntrance($event, $participant, $status)
  {
    try {
      Invites::where('event_id', $event)
        ->where('participant_id', $participant)
        ->update(['status' => $status]);
      return response()->json(['message' => 'Success']);
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
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


  private function generateQrcode(int $eventId,  int $participantId)
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

  public function removeParticipantFromEvent(Request $request)
  {
    try {
      Invites::where('participant_id', $request->participant_id)
        ->where('event_id', $request->event_id)
        ->delete();

      return redirect()->back()->with('success', 'O participante foi removido do evento com sucesso.');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function changeParticipantType(Request $request)
  {
    try {
      Invites::where('participant_id', $request->participant_id)
        ->where('event_id', $request->event_id)
        ->update(['participant_type_id' => $request->type]);

      return redirect()->back()->with('success', 'Tipo de participação alterado com sucesso.');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function openRoom($hash)
  {
    return view('events.displayCode', compact('hash'));
  }

  public function attend($hash)
  {
    $event = Event::where('hash', $hash)->first();

    return view('events.attend', compact('event'));
  }


  public function entrance(Request $request, $encryptedevent)
  {
    // Validate input
    $request->validate([
      'contacto' => 'required|string|max:255',
    ]);

    $contact = $request->input('contacto');

    // Lookup event by encrypted hash
    $event = Event::where('hash', $encryptedevent)->firstOrFail();

    // Try to find participant by email or phone
    $participant = Participant::where(function ($query) use ($contact) {
      $query->where('email', $contact)
        ->orWhere('phone_number', $contact);
    })->first();

    if (!$participant) {
      return back()->withErrors(['contacto' => 'Participante não encontrado para este evento.']);
    }

    // Check if already marked
    $alreadyPresent = Invites::select('status')
      ->where('event_id', $event->id)
      ->where('participant_id', $participant->id)
      ->first();

    if ($alreadyPresent === 'Presente') {
      return back()->with('message', 'Presença já confirmada.');
    }

    $response = $this->confirmEntrance($event->id, $participant->id, "Presente");
    if ($response->getStatusCode() === 200) {
      return back()->with('message', 'Presença confirmada com sucesso!');
    }

    return back()->with('message', 'Erro ao processar pedido!');
  }
}
