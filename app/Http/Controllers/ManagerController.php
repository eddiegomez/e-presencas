<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ManagerController extends Controller
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
    'organization.required' => 'O campo instituição é obrigatório.',
    'organization.integer' => 'O campo instituição foi mal informado.',
    'organization.exists' => 'Não encontramos a instituição que nos informou.',
  ];

  /**
   * Display a listing of the resource.
   *
   * @return View
   */
  public function index(Request $request)
  {
    $gestores = User::role('gestor')->paginate(12);
    $organizations = Organization::all();
    // dd($gestores->isEmpty());

    return view('managers.list', compact('gestores', 'organizations'));
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
        'organization' => ['required', 'integer', 'exists:organization,id']
      ], $this->customMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $gestor = new User();
    $organization = Organization::find($data['organization']);

    if (User::where('phone', "+258" . $data['phone'])->exists()) {
      return redirect()->back()->with('warning', 'Este numero de celular ja foi registado!')->withInput();
    }

    if (User::where('email', $data['email'])->exists()) {
      return redirect()->back()->with('warning', 'Este email ja foi registado!')->withInput();
    }

    $gestor->name = $data['name'];
    $gestor->email = $data['email'];
    $gestor->phone = '+258' . $data['phone'];
    $gestor->organization_id = $data['organization'];
    $gestor->password = Hash::make("Gestor@" . $organization->name);
    $gestor->save();

    $gestor->assignRole('gestor');
    return redirect()->back()->with('success', 'Gestor adicionado com successo!');
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
        'organization' => ['required', 'integer', 'exists:organization,id']
      ], $this->customMessages += $editMessages);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $manager = User::find($data['id']);

    if ($manager->getRoleNames()[0] !== 'gestor') {
      return redirect()->back()->with('warning', 'The user cannot be deleted.');
    }

    $manager->name = $data['name'];
    $manager->email = $data['email'];
    $manager->phone = $data['phone'];
    $manager->organization_id = $data['organization'];
    $manager->update();

    return redirect()->back()->with('success', 'Gestor foi actualizado com sucesso!');
  }

  public function changePwd(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'manPassword' => ['required']
      ], $this->customMessages += ['id' => 'Erro de formulação do ID']);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $manager = User::findOrFail($data['id']);

    if (!$manager) {
      return redirect()->back()->with('warning', 'Nenhum gestor encontrado');
    }

    $manager->password = Hash::make($data['manPassword']);
    $updated = $manager->update();

    if ($updated) {
      return redirect()->back()->with('success', 'Palavra-passe alterada com sucesso!');
    } else {
      return redirect()->back()->with('error', 'Erro de processamento');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer'],
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $manager = User::find($data['id']);


    // Check if ID corresponds to a manager
    if ($manager->getRoleNames()[0] !== 'gestor') {
      return redirect()->back()->with('warning', 'The user cannot be deleted.');
    }
    // Delete the Manager
    $manager->delete();

    return redirect()->back()->with('success', 'The manager has been deleted successfully.');
  }
}
