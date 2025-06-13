<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Event_Address;
use App\Models\Invites;
use App\Models\Schedule;
use App\Notifications\sendInvite;
use App\Models\Event;
use App\Models\Participant;
use App\Models\ParticipantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View as View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Models\StaffEvento;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{

  // Custom input validation error messages
  private $InputCustomMessages = [
    'name.required' => 'O evento deve ter um nome.',
    'name.string' => 'O nome deve ser uma string.',
    'name.max' => 'O nome do evento não pode ser um texto!',
    'banner.required' => 'Submeta uma imagem como banner, se não  tiver um banner submeta o logotipo.',
    'banner.image' => 'O banner deve ser uma imagem.',
    'banner.mimes' => 'A imagem deve estar no formato jpg ou png.',
    'start_date.required' => 'A data de ínicio é obrigatória.',
    'start_date.date' => 'A data de ínicio deve ter um formato data.',
    'start_date.date_format' => 'A data de ínicio deve ter um formato data yyyy-mm-dd.',
    'start_date.after_or_equal' => 'A data de ínicio não pode ser menor que a data actual.',
    'end_date.required' => 'A data de fim é obrigatória.',
    'end_date.date' => 'A data de fim deve ter um formato data.',
    'end_date.date_format' => 'A data de fim deve ter um formato data yyyy-mm-dd.',
    'end_date.after_or_equal' => 'A data de fim não pode ser menor que a data de ínicio.',
    'start_time.required' => 'A hora de ínicio é obrigatória.',
    'start_time.date_format' => 'A hora de ínicio deve ter formato de hora.',
    'end_time.required' => 'A hora de fim é obrigatória.',
    'end_time.date_format' => 'A hora de fim deve ter formato de hora.',
  ];

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $events = Event::where('organization_id', Auth::user()->organization_id)->get();
    $addresses = Address::all();

    return response(view('events.list', compact('events', 'addresses')));
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    try {
      $this->validate(request(), [
        'name' => ['required', 'string', 'max:200'],
        'banner' => ['required', 'image', 'mimes:png,jpg'],
        'start_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
        'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        'start_time' => ['required', 'date_format:H:i'],
        'end_time' => ['required', 'date_format:H:i',],
        'description' => ['required'],
      ], $this->InputCustomMessages);
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
    } else {
      $address = $data['address'];
    }

    $bannerUrl = $request->file('banner')->store('banners', 'public');
    $event = new Event();
    //geracao do hash
    $hash = substr(dechex(crc32($event->id . '-' . $event->organization_id)), 0, 5);
    $event->name = $data['name'];
    $event->start_date = $data['start_date'];
    $event->end_date = $data['end_date'];
    $event->start_time = $data['start_time'];
    $event->end_time = $data['end_time'];
    $event->description = $data['description'];
    $event->banner_url = $bannerUrl;
    $event->hash = $hash;
    $event->organization_id = Auth::user()->organization_id;
    $event->save();

    $event_address = new Event_Address();
    $event_address->event_id = $event->id;
    if ($data['address'] === 'new') {
      $event_address->address_id = $address->id;
    } else {
      $event_address->address_id = $address;
    }
    $event_address->save();

    return redirect()->back()->with('success', 'O evento foi criado com sucesso!');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return View
   */
  public function show($id)
  {
    try {
      $event = Event::find($id);
      $addresses = Address::all();
      $participants = Participant::all();
      $participant_type = ParticipantType::all();
      $staffs = User::leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->leftJoin('staff_evento', 'model_has_roles.model_id', '=', 'staff_evento.staff_id')
        ->select('users.id', 'users.name', 'users.email', 'users.phone', 'staff_evento.evento_id', 'staff_evento.id as staff_evento_id')
        ->where('organization_id', Auth::user()->organization_id)
        ->where('model_has_roles.role_id', 3)
        ->get();
      return view('events.single', compact('event', 'participants', 'participant_type', 'addresses', 'staffs'));
    } catch (\Throwable $th) {
      dd('ERRO!!');
      throw $th;
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return RedirectResponse
   */
  public function update(Request $request, $id): RedirectResponse
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

    $data = $request->only(['name', 'date', 'banner', 'description', 'start_date', 'end_date', 'start_time', 'end_time']);
    $data = array_filter($data, function ($value) {
      return $value !== null;
    });

    $event->update($data);

    return redirect()->back()->with('success', 'O evento foi editado com sucesso!');
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
    return redirect()->route('event.list');
  }


  public function inviteParticipant(Request $request, $id)
  {

    try {
      $validator = Validator::make($request->all(), [
        'participant' => ['required'],
        'type' => ['required', 'numeric']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
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
      } catch (\Throwable $th) {
        throw $th;
      }

      $data = $request->all();

      $participant = new Participant();

      $participant->name = $data['name'];
      $participant->email = $data['email'];
      $participant->description = $data['description'];
      $participant->phone_number = $data['phone_number'];
      $participant->degree = $data['degree'];
      $participant->save();
    } else {
      $participant = Participant::find($data['particpant']);
    }


    $qrCodeName = base64_encode($id . $participant->id);

    $participant_event = new Invites();
    $participant_event->participant_id = $participant->id;
    $participant_event->event_id = $id;
    $participant_event->participant_type_id = $data['type'];
    $participantEmail = $participant->email;
    // Mail::to($participantEmail)->send(new sendInvite);

    //$rsp = $this->generateQrcode($participant_event->qr_url, $participant_event);

    Notification::route('mail', $participantEmail)->notify(new sendInvite(
      $participant_event->event_id,
      $participant_event->participant_id
    ));

    $participant_event->save();
    return redirect()->back()->with('Success', 'O participante ' . $participant_event->name . ' Foi convidado');
  }

  public function createSchedule(Request $request, $id)
  {
    $validateScheduleMessages = [
      'name.required' => 'O programa deve ter um nome.',
      'name.string' => 'O nome do programa deve ser texto',
      'date.required' => 'O programa deve ter uma data.',
      'date.date' => 'A data do programa deve ser do tipo data.',
      'schedule.required' => 'O programa deve ter um pdf.',
      'schedule.mimetypes' => 'O programa deve ter uma extensao pdf.'
    ];

    try {
      $validator = Validator::make($request->all(), [
        'name' => ['required', 'string'],
        'date' => ['required', 'date'],
        'schedule' => ['required', 'mimetypes:application/pdf'],
      ], $validateScheduleMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
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

      return redirect()->back()->with('Success', 'Agenda adicionada com sucesso');
    }

    return redirect()->back()->with('error', 'Something went wrong!');
  }

  public function removeSchedule(Request $request)
  {
    try {
      $schedule = Schedule::find($request->schedule_id);
      $schedule->delete();
      return redirect()->back()->with('Success', 'Agenda removida com sucesso');
    } catch (\Throwable $th) {
      throw $th;
      return redirect()->back()->with('error', 'Something went wrong!');
    }


    return redirect()->back()->with('Success', 'Schedule created sucessfully');
  }

  private function generateQrcode($name, Invites $participant_event)
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

  public function addStaff(Request $request)
  {
    $validateStaffEventoMessages = [
      'staff_id.required' => 'Nenhum protocolo foi associado',
      'evento_id.required' => 'Nenhum evento associado',
    ];

    try {
      $validator = Validator::make($request->all(), [
        'staff_id' => ['required'],
        'evento_id' => ['required'],
      ], $validateStaffEventoMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $staff_evento = new StaffEvento();
      $staff_evento->staff_id = $request->staff_id;
      $staff_evento->evento_id = $request->evento_id;
      $staff_evento->save();
      return redirect()->back()->with('Success', 'Protocolo adicionado com sucesso');
    } catch (\Throwable $th) {
      throw $th;
      return redirect()->back()->with('Error', 'Erro ao adicionar protocolo');
    }
  }

  public function applyEvent($event_hash)
  {
    try {
      $event = Event::leftJoin('organization', 'organization.id', '=', 'events.organization_id')
        ->leftJoin('event_address', 'event_address.event_id', '=', 'events.id')
        ->leftJoin('address', 'address.id', '=', 'event_address.address_id')
        ->select('events.*', 'organization.name as organizer', 'address.name as address', 'address.url as address_url')
        ->where('hash', $event_hash)
        ->first();
      if ($event) {
        $schedules = Schedule::where('event_id', $event->id)->get();

        return response(view('events.details', compact('event', 'schedules')));
      }else{
        return "nenhum evento";
      }
    } catch (\Throwable $th) {
      throw $th;
      return redirect()->back()->with('Error', 'Erro ao processar pedido');
    }
  }
}
