<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{

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
    $user = Auth::user();
    $participants = Participant::all();
    return response(view("participants", compact("user", "participants")));
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
        'phone_number' => ['required', 'numeric'],
        'description' => ['required', 'string']
      ], $this->customValidatorMessages);

      // dd($validator);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
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

    return redirect()->back()->with('message', 'O ' . $participant->name . 'foi criado!');
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

    return response(view('singleParticipant', compact('user', 'participant')));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
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
      $validator = Validator::make($request->all(), [
        "name" => ["required", "string"],
        "description" => ["required", "string"],
        "phone_number" => ["required", "numeric"],
        "email" => ["required", "email"],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $e) {
      throw $e;
    }

    $participant = Participant::find($id);
    $participant->name = $request->name;
    $participant->description = $request->description;
    $participant->phone_number = $request->phone_number;
    $participant->email = $request->email;

    $participant->update();

    return redirect()->back()->with('success', 'Participante foi editado com sucesso!');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $participant = Participant::find($id);

    if ($participant) {
      $participant->delete();
      return redirect()->route('participant.index')->with('success', 'Participante de nome ' . $participant->name . 'foi apagado!');
    }
    return redirect()->back()->with('error', 'Esse participante nao existe!');
  }
}
