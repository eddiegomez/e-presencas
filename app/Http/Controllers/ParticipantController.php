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
    //
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
  public function destroy($id)
  {
    //
  }

  public function showBusinessCard($hashed_mail)
  {
    //http://127.0.0.1:8000/businessCard/ZWRzb24uZ29tZXNAaW5hZ2UuZ292Lm16
    $participant = Participant::where('email',base64_decode($hashed_mail))->first();
    return response(view("businessCard", compact("participant", "participant")));
  }
}
