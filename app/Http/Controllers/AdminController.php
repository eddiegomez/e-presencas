<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
  protected $customMessages = [
    'name.required' => 'O campo nome é obrigatório.',
    'name.max' => 'O campo nome deve ter no máximo 200 caracteres',
    'name.regex' => 'O campo nome deve nao pode conter numeros.',
    'email.required' => 'O campo email é obrigatório.',
    'email.email' => 'O campo email não foi preenchido devidamente.',
    'phone.required' => 'O campo número de telefone é obrigatório.',
    'phone.numeric' => 'O campo número deve ser númerico.',
    'phone.digits' => 'O campo número deve ter exactamente 9 caracteres.',
  ];

  /**
   * Display a listing of the resource.
   *
   * @return View
   */
  public function index()
  {
    $admins = User::role('gestor do sistema')->get();


    return view('admin.list', compact('admins'));
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
        'name' => ['required', 'max:255', 'string', 'regex:/^[^\d]+$/'],
        'email' => ['required', 'max:255', 'email'],
        'phone' => ['required', 'digits:9', 'string'],
      ], $this->customMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->all();
      $admin = new User();

      if (User::where('phone', "+258" . $data['phone'])->exists()) {
        return redirect()->back()->with('warning', 'Este numero de celular ja foi registado!')->withInput();
      }

      if (User::where('email', $data['email'])->exists()) {
        return redirect()->back()->with('warning', 'Este email ja foi registado!')->withInput();
      }

      $admin->name = $data['name'];
      $admin->email = $data['email'];
      $admin->phone =  $data['phone'];
      $admin->organization_id = 1;
      $admin->password = Hash::make("presencas12$%");
      $admin->save();
      $admin->assignRole("gestor do sistema");

      return redirect()->back()->with('success', 'Administrador criado com sucesso');
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
    $editMessages = [
      'id.exists' => 'Gestor inexistente!',
      'id.integer' => 'ID invalido.',
      'id.required' => 'ID é obrigatório.'
    ];

    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'max:255', 'string', 'regex:/^[^\d]+$/'],
        'email' => ['required', 'max:255', 'email'],
        'phone' => ['required', 'digits:9', 'string'],
      ], $this->customMessages += $editMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }

      $data = $request->all();
      $user = User::find($data['id']);

      $user->name = $data['name'];
      $user->email = $data['email'];
      $user->phone = $data['phone'];
      $user->update();

      return redirect()->back()->with('success', 'O administrador foi editado com sucesso!');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer', 'exists:users,id'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }



      $user = User::find($request->id);

      if ($user->email == "dtd@inage.gov.mz") {
        return redirect()->back()->with('warning', 'Impossivel apagar este usuario');
      }
      $user->delete();


      return redirect()->back()->with('success', 'Administrador apagado com sucesso');
    } catch (Exception $e) {
      $errorMessage = $e->getMessage();

      return redirect()->back()->with('error', $errorMessage);
    }
  }
}
