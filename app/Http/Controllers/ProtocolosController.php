<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ProtocolosController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();
    $protocolos = User::where('user_role', 2)->get();

    return response(view('Protocolos.index', compact('user', 'protocolos')));
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
        'name' => ['required', 'string'],
        'email' => ['required', 'string'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $password = "MoreN37#123";
    $role = 2;

    $protocolo = new User();

    $protocolo->name = $data["name"];
    $protocolo->email = $data["email"];
    $protocolo->password = Hash::make($password);
    $protocolo->user_role = $role;
    $protocolo->save();
    Notification::route('mail', $protocolo->email)->notify(new EmailVerification(
      $protocolo->id,
      $protocolo->email,
      $protocolo->name
    ));

    return redirect()->back()->with('success', 'Protocolo criado com Sucesso!');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function emailConfirmation($encryptedId)
  {
    $id = base64_decode($encryptedId);
    $user = User::find($id);

    return response(view('Protocolos.emailConfirmation', compact('user')));
  }


  /**
   * Confirm the Registration and Password update
   * @param \Illuminate\Http\Request $request
   * @param string $encryptedId
   * @return \Illuminate\Http\RedirectResponse
   */
  public function confirmRegister(Request $request, String $encryptedId)
  {
    // dd($request->all());
    try {
      $validator = Validator::make($request->all(), [
        'defaultPassword' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $id = base64_decode($encryptedId);
    $user = User::find($id);

    if (Hash::check($request->defaultPassword, $user->password)) {
      $user->password = Hash::make($request->password);
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->save();

      return redirect()->route('login');
    }

    return redirect()->back()->with('error', 'Default password is incorrect');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "Eid" => ["required", "numeric"],
        "Ename" => ["required", "string"],
        "Eemail" => ["required", "email"]
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $protocolo = User::find($data['Eid']);

    $protocolo->name = $data['Ename'];
    $protocolo->email = $data['Eemail'];
    $protocolo->update();

    return redirect()->back()->with('success', 'O protocolo foi editado com sucesso!');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "protocoloId" => ["required", "numeric"]
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    User::where('id', $data['protocoloId'])->delete();

    return redirect()->back()->with('success', 'O protocolo foi removido com sucesso!');
  }
}
