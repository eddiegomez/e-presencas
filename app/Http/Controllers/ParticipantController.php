<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
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
    try {
      $validator = Validator::make($request->all(), [
        'name' => ['required', 'string'],
        'email' => ['required', 'email'],
        'phone_number' => ['required', 'numeric'],
        'description' => ['required', 'string']
      ]);

      // dd($validator);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      // dd($th);
      throw $th;
    }

    $data = $request->all();
    // dd($data);

    $participant = new Participant();

    $participant->name = $data['name'];
    $participant->email = $data['email'];
    $participant->description = $data['description'];
    $participant->phone_number = $data['phone_number'];
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
