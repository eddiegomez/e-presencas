<?php

namespace App\Http\Controllers;

use App\Notifications\sendInvite;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Participant_Has_Event;
use App\Models\ParticipantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
    // dd($events);

    return view('event', compact('user', 'events'));
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
        'date' => ['required', 'date'],
        'banner' => ['required', 'image'],
      ]);
    } catch (\Throwable $e) {
      throw $e;
    }

    $data = $request->all();
    // dd($data);  
    // $bannerUrl = time() . '.' . $data['name'];
    // dd($bannerUrl);
    $bannerUrl = $request->file('banner')->store('banners', 'public');
    $event = new Event();
    $event->name = $data['name'];
    $event->date = $data['date'];
    $event->banner_url = $bannerUrl;
    $event->save();

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
      dd($th);
      throw $th;
    }


    $data = $request->all();
    $qrCodeName = base64_encode($id . $data['participant']);

    $participant_event = new Participant_Has_Event();
    $participant_event->participant_id = $data['participant'];
    $participant_event->event_id = $id;
    $participant_event->participant_type_id = $data['type'];
    $participant_event->qr_url = $qrCodeName;
    $rsp = $this->generateQrcode($participant_event->qr_url);

    // dd($participant_event);


    if ($rsp == 0) {
      return redirect()->back()->with('error', 'Algo de errado nao esta certo!');
    } else {
      $participant_event->save();
      return redirect()->back()->with('Success', 'O participante ' . $participant_event->name . ' Foi convidado');
    }
  }

  private function generateQrcode($name)
  {
    $qrCode = QrCode::format('svg')->size(100)->generate('QR code Message');
    $qrCodePath = storage_path('app/public/qrcodes/' . $name . '.svg');
    $resp = file_put_contents($qrCodePath, $qrCode);
    return $resp;
  }
}
