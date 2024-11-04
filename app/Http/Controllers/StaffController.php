<?php

namespace App\Http\Controllers;

use App\Models\StaffEvento;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Services\StaffService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

  protected $staffService;

  public function __construct(StaffService $staffService)
  {
    $this->staffService = $staffService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = Auth::user();
    $protocolos = User::role('protocolo')->where('organization_id', Auth::user()->organization_id)->get();
    return response(view('staff.index', compact('user', 'protocolos')));
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
        'phone' => ['required', 'numeric', 'digits:9']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $organization = auth()->user()->organization_id;

      $protocolo = $this->staffService->create(
        $request->name,
        strtolower($request->email),
        $request->phone,
        $organization
      );

      return redirect()->back()->with('success', 'O protocolo de nome ' . $protocolo->name . ' foi adicionado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  /**
   * Confirm staff member email
   *
   * @param  string  $encryptedId
   * @return \Illuminate\Http\Response
   */
  public function emailConfirmation($encryptedId)
  {
    Auth::logout();
    $id = base64_decode($encryptedId);
    $user = User::find($id);

    return response(view('staff.emailConfirmation', compact('user')));
  }


  /**
   * Confirm the Registration and Password update
   * @param \Illuminate\Http\Request $request
   * @param string $encryptedId
   * @return \Illuminate\Http\RedirectResponse
   */
  public function emailConfirmationPost(Request $request, String $encryptedId)
  {
    try {
      $validator = Validator::make($request->all(), [
        'defaultPassword' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $this->staffService->confirmRegistration($encryptedId, $request->defaultPassword, $request->password);

      return redirect()->route('login');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "id" => ["required", "integer"],
        "name" => ["required", "string"],
        "email" => ["required", "email"],
        "phone" => ["required", "numeric"]
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $staff = $this->staffService->update(
        $request->id,
        $request->name,
        strtolower($request->email),
        $request->phone
      );

      return redirect()->back()->with('success', 'Os dados de ' . $staff->name . ' foi actualizado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', 'Houve algum erro durante a actualização de dados tente novamente!');
    }
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
        "id" => ["required", "integer"]
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $this->staffService->delete($request->id);

      return redirect()->back()->with('success', 'O participante foi apagado com suceso.');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  public function removeStaffFromEvent(Request $request)
  {
    try {

      $staffEvent = StaffEvento::find($request->staff_evento_id);
      $staffEvent->delete();

      return redirect()->back()->with('success', 'O protocolo foi removido com sucesso.');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }
}
