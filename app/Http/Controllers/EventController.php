<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Event_Has_Address;
use App\Models\Schedule;
use App\Notifications\sendInvite;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Participant_Has_Event;
use App\Models\ParticipantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();
    $events = Event::all();
    $addresses = Address::all();

    return response(view('event', compact('user', 'events', 'addresses')));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $this->validate(request(), [
        'name' => ['required', 'string'],
        'banner' => ['required', 'image'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date'],
        'start_time' => ['required', 'date_format:H:i'],
        'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
      ]);
    } catch (\Throwable $e) {
      throw $e;
    }



    $data = $request->all();
    // dd($data);  
    // $bannerUrl = time() . '.' . $data['name'];
    // dd($bannerUrl);

    if ($data['address'] === 'new') {
      $validator = Validator::make($request->all(), [
        'newLocation' => ['required', 'string'],
        'url' => ['required', 'url'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $address = new Address();

      $address->name = $data['newLocation'];
      $address->url = $data['url'];
      $address->save();
    }

    $bannerUrl = $request->file('banner')->store('banners', 'public');
    $event = new Event();
    $event->name = $data['name'];
    $event->start_date = $data['start_date'];
    $event->end_date = $data['end_date'];
    $event->start_time = $data['start_time'];
    $event->end_time = $data['end_time'];
    $event->banner_url = $bannerUrl;
    $event->save();

    $event_address = new Event_Has_Address();
    $event_address->event_id = $event->id;
    $event_address->address_id = $address->id;

    return redirect()->back();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = Auth::user();
    $event = Event::find($id);
    $participants = Participant::all();
    $participant_type = ParticipantType::all();
    return response(view('singleEvent', compact('user', 'event', 'participants', 'participant_type')),);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    try {
      $this->validate(request(), [
        'name' => ['string'],
        'date' => ['date'],
        'banner' => ['image'],
      ]);
    } catch (\Throwable $th) {
      throw $th;
    }

    $event = Event::findOrFail($id);

    $data = $request->only(['name', 'date', 'banner']);

    $data = array_filter($data, function ($value) {
      return $value !== null;
    });

    $event->update($data);

    return redirect()->route('events');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $event = Event::findOrFail($id);
    if ($event) {
      Event::destroy($id);
    }

    return redirect()->route('events');
  }


  public function inviteParticipant(Request $request, $id)
  {

    try {
      $validator = Validator::make($request->all(), [
        'participant' => ['required', 'numeric'],
        'type' => ['required', 'numeric']
      ]);
      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }


    $data = $request->all();
    $qrCodeName = base64_encode($id . $data['participant']);

    $participant_event = new Participant_Has_Event();
    $participant_event->participant_id = $data['participant'];
    $participant_event->event_id = $id;
    $participant_event->participant_type_id = $data['type'];
    $participant_event->qr_url = $qrCodeName;
    $participant = Participant::find($participant_event->participant_id);
    $participantEmail = $participant->email;
    // Mail::to($participantEmail)->send(new sendInvite);

    $rsp = $this->generateQrcode($participant_event->qr_url, $participant_event);

    if ($rsp == 0) {
      return redirect()->back()->with('error', 'Algo de errado nao esta certo!');
    } else {
      Notification::route('mail', $participantEmail)->notify(new sendInvite(
        $participant_event->event_id,
        $participant_event->participant_id,
        $participant_event->qr_url
      ));

      $participant_event->save();
      return redirect()->back()->with('Success', 'O participante ' . $participant_event->name . ' Foi convidado');
    }
  }

  public function createSchedule(Request $request, $id)
  {
    try {

      // dd($request);
      $this->validate(request(), [
        'name' => ['required', 'string'],
        'date' => ['required', 'date'],
        'schedule' => ['required', 'mimetypes:application/pdf'],
      ]);

      // if ($validator->fails()) {
      //   return redirect()->back()->withErrors($validator)->withInput();
      // }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $encryptedEvent = base64_encode($id);
    // $savePdf = file_put_contents(storage_path('app/public/schedules/schedule-' . $encryptedEvent . '-' . $data['date'] . '.pdf'), $data['schedule']);

    if ($request->file('schedule')->isValid()) {
      $file = $request->file('schedule');
      $scheduleName = 'schedule-' . $encryptedEvent . '-' . $data['date'] . '.' . $file->getClientOriginalExtension();
      $file->storeAs('schedules', $scheduleName, 'public');

      $schedule = new Schedule();
      $schedule->name = $data['name'];
      $schedule->date = $data['date'];
      $schedule->event_id = $id;
      $schedule->pdf_url = $scheduleName;
      $schedule->save();

      return redirect()->back()->with('Success', 'Schedule created sucessfully');
    }


    return redirect()->back()->with('error', 'Something went wrong!');
  }
  private function generateQrcode($name, Participant_Has_Event $participant_event)
  {
    $encryptedEvent = base64_encode($participant_event->event_id);
    $encryptedParticipant = base64_encode($participant_event->participant_id);

    $qrCode = QrCode::format('svg')->size(100)->generate(route(
      'confirmPresenceShow',
      ['encryptedevent' => $encryptedEvent, 'encryptedparticipant' => $encryptedParticipant]
    ));
    $qrCodePath = storage_path('app/public/qrcodes/' . $name . '.svg');
    $resp = file_put_contents($qrCodePath, $qrCode);
    return $resp;
  }
}
