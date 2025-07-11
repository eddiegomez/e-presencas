<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invites;
use App\Models\Participant;
use App\Models\ParticipantType;
use App\Models\StaffEvento;
use App\Services\ParticipantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ShareVCardMail;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ParticipantController extends Controller
{

  protected $participantService;

  public function __construct(ParticipantService $participantService)
  {
    $this->participantService = $participantService;
  }

  public $customValidatorMessages = [
    "name.required" => "O participante deve ter um nome.",
    "name.string" => "O nome do participante deve ser um texto.",
    "name.regex" => "O nome do participante não pode conter numeros",
    "last_name.regex" => "O sobrenome do participante não pode conter numeros",
    "email.required" => "O paricipante deve ter um email",
    "email.email" => "O email do inserido não é valido.",
    "degree.string" => "O grau deve ser um texto",
    "phone.required" => "O participante deve ter um número de telefone.",
    "phone.numeric" => "O numero de telefone deve não pode conter letras.",
    "description.required" => "O participante deve ser descrevido.",
    "description.string" => "A descrição deve ser um texto"
  ];
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $participants = Participant::where('organization_id', Auth::user()->organization_id)->get();

    return response(view("participants.list", compact("participants")));
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
      $validator = Validator::make($request->all(), [
        'name' => ['required', 'string', 'regex:/^[^\d]+$/'],
        'last_name' => ['required', 'string', 'regex:/^[^\d]+$/'],
        'email' => ['required', 'email'],
        'degree' => ['required', 'string'],
        'phone' => ['required', 'numeric',  'digits:9'],
        'description' => ['string']
      ], $this->customValidatorMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      if ($request->upload) {
        $profile_url = $request->file('upload')->store('profiles', 'public');
      } else {
        $profile_url = NULL;
      }

      $data = $request->all();
      $participant = $this->participantService->createParticipant(
        $data['name'],
        $data['last_name'],
        $data['email'],
        $data['description'],
        $data['phone'],
        $data['degree'],
        $profile_url
      );

      $this->shareVFCard($participant->id);

      return redirect()->back()->with('success', 'O participante ' . $participant->name . ' ' . $participant->last_name . ' foi registado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage)->withInput();
    }
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
    $participant = DB::table('participants')
      ->leftjoin('organization', 'organization.id', '=', 'participants.organization_id')
      ->select('participants.*', 'organization.name as nome_org', 'organization.website')
      ->where('participants.id', $id)
      ->first();
    $events = Event::where('organization_id', $user->organization_id)->get();
    $participant_type = ParticipantType::all();
    return response(view('participants.single', compact('user', 'participant', 'events', 'participant_type')));
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, int $id)
  {
    try {
      $validator = Validator::make($request->all(), [
        "name" => ["required", "string"],
        "last_name" => ["required", "string"],
        "description" => ["required", "string"],
        "phone_number" => ["required", "numeric"],
        "degree" => ["required", "string"],
        "email" => ["required", "email"],
      ], $this->customValidatorMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->only(['name', 'last_name', 'description', 'phone_number', 'degree', 'email']);

      $this->participantService->update($data, $id);

      return redirect()->back()->with('success', 'Participante foi editado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
      return redirect()->back()->with('error', $errorMessage);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(int $id)
  {
    try {
      $this->participantService->delete($id);
      return redirect()->route('participant.index')->with('success', 'Participante eliminado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function showBusinessCard($hashed_mail)
  {
    try {
      $participant = DB::table('participants')
        ->leftjoin('organization', 'organization.id', '=', 'participants.organization_id')
        ->select('participants.*', 'organization.name as nome_org', 'organization.website', 'organization.location')
        ->where('participants.email', base64_decode($hashed_mail))
        ->first();
      $eventos = [];
      $invite = null;
      if (auth()->check() && Auth::user()->roles->contains('id', 3)) {
        $currentDateTime = Carbon::now(); // Get the current date and time
        $eventos = StaffEvento::leftjoin('events', 'events.id', '=', 'staff_evento.evento_id')
          ->select('events.id as event_id', 'events.start_date', 'events.end_date', 'events.start_time', 'events.end_time')
          ->where('staff_id', Auth::user()->id)
          ->where(function ($query) use ($currentDateTime) {
            $query->where(function ($subQuery) use ($currentDateTime) {
              $subQuery->where('events.start_date', '<=', $currentDateTime->toDateString())
                ->where('events.start_time', '<=', $currentDateTime->toTimeString());
            })
              ->where(function ($subQuery) use ($currentDateTime) {
                $subQuery->where('events.end_date', '>=', $currentDateTime->toDateString())
                  ->where('events.end_time', '>=', $currentDateTime->toTimeString());
              });
          })
          ->get();
        if (sizeof($eventos) > 0)
          $invite = DB::table('invites')
            ->select('status')
            ->where('event_id', $eventos[0]->event_id)
            ->where('participant_id', $participant->id)
            ->first();
      }

      if ($participant != null) {
        return response(view("businessCard", compact("participant", "participant", "eventos", "invite")));
      }
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function register(Request $request)
  {
    $validated = $request->validate([
      'name'         => 'required|string|max:255',
      'last_name'    => 'required|string|max:255',
      'email'        => 'required|email|max:255',
      'phone_number' => 'required|string|min:9|max:12',
      'degree'       => 'required|string|max:255',
      'description'  => 'nullable|string',
      'event_id'     => 'required|exists:events,id',
    ]);

    $participant = DB::table('participants')->where('email', $request->email)->first();

    if (!$participant) {
      $participant = Participant::create($validated);
    }

    if ($participant) {
      $invite = DB::table('invites')->where('participant_id', $participant->id)->where('event_id', $request->event_id)->first();
      if (!$invite) {
        Invites::create([
          'participant_id'      => $participant->id,
          'event_id'            => $request->event_id,
          'status'              => "Confirmado",
          'participant_type_id' => 1,
        ]);
        return back()->with('success', 'Registo efectuado com sucesso!');
      }
      return back()->with('success', 'Já encontra-se registado a este evento!');
    }
  }

  public function shareVFCard($id)
  {
    $participant = DB::table('participants')
      ->leftjoin('organization', 'organization.id', '=', 'participants.organization_id')
      ->select('participants.*', 'organization.name as nome_org', 'organization.website', 'organization.location')
      ->where('participants.id', $id)
      ->first();

    if (!$participant) {
      return "Participante não encontrado.";
    }

    $fileName = 'qrcode_' . uniqid() . '.png';
    $filePath = storage_path('app/public/' . $fileName);

    QrCode::format('png')
      ->size(180)
      ->style('round')
      ->eye('circle')
      ->generate(
        'https://assiduidade.inage.gov.mz/showBusinessCard/' . base64_encode($participant->email),
        $filePath
      );

    Mail::to($participant->email)->send(new ShareVCardMail($participant, $filePath));

    // Limpa imagem temporária
    unlink($filePath);

    return "Email enviado com sucesso.";
  }
}
