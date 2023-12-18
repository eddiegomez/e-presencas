<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Services\ParticipantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    "name.regex" => "O nome do participante nao pode conter numeros",
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
    $participants = Participant::all();
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
        'email' => ['required', 'email'],
        'degree' => ['required', 'string'],
        'phone' => ['required', 'numeric',  'digits:9'],
        'description' => ['required', 'string']
      ], $this->customValidatorMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->all();
      $participant = $this->participantService->createParticipant(
        $data['name'],
        $data['email'],
        $data['description'],
        $data['phone'],
        $data['degree']
      );

      return redirect()->back()->with('success', 'O ' . $participant->name . 'foi criado!');
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
    $participant = Participant::find($id);

    return response(view('participants.single', compact('user', 'participant')));
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
        "description" => ["required", "string"],
        "phone_number" => ["required", "numeric"],
        "degree" => ["required", "string"],
        "email" => ["required", "email"],
      ], $this->customValidatorMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->only(['name', 'description', 'phone_number', 'degree', 'email']);

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
}
