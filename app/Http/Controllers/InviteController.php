<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Participant_Has_Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InviteController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
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
    $rs = Participant_Has_Event::where([['event_id', $eventId], ['participant_id', $participantId]])
      ->update(['status' => 'Confirmada']);

    $successMessage = session('success');


    $event = Event::find($eventId);
    $participant = Participant::find($participantId);

    // dd(session());

    return response(view('confirmPresence', compact('successMessage', 'event', 'participant', 'encryptedevent', 'encryptedparticipant'))->with('success', 'A sua presenca foi confirmada no evento ' . $event->name));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
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
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    dd($request->all());
  }

  public function confirmEntrance($encryptedevent, $encryptedparticipant)
  {
    $user = Auth::user();
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);
    $event = Event::find($eventId);
    $participant = Participant::find($participantId);
    $invite = Participant_Has_Event::where([['event_id', $eventId], ['participant_id', $participantId]])->first();


    return response(view('confirmEntrance', compact('user', 'event', 'encryptedevent', 'encryptedparticipant', 'participant', 'invite')));
  }

  public function confirmEntrancePost($encryptedevent, $encryptedparticipant)
  {
    $eventId = base64_decode($encryptedevent);
    $participantId = base64_decode($encryptedparticipant);

    $event = Event::where("id", $eventId)->first();


    $rs = Participant_Has_Event::where([['event_id', $eventId], ['participant_id', $participantId]])
      ->update(['status' => 'Presente']);


    return redirect()->back()->with('success', 'A presenca do participante foi actualizada com sucesso!');
  }

  // public function confirmPresence($encryptedevent, $encryptedparticipant)
  // {
  //   // return redirect()->route('event', 1)->with('message', 'IT WORKS!');
  //   $eventId = base64_decode($encryptedevent);
  //   $participantId = base64_decode($encryptedparticipant);


  //   $invite = Participant_Has_Event::where(["event_id" => $eventId, "participant_id" => $participantId])->first();
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
