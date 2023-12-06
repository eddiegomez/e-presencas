<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class OrganizationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $organizations = Organization::paginate(12);

    return response(view('organization.list', compact('organizations')));
  }

  protected $customMessages = [
    'name.required' => 'O campo nome é obrigatório.',
    'name.max' => 'O campo nome deve ter no máximo 200 caracteres',
    'name.regex' => 'O campo nome deve nao pode conter numeros.',
    'email.required' => 'O campo email é obrigatório.',
    'email.email' => 'O campo email não foi preenchido devidamente.',
    'phone.required' => 'O campo número de telefone é obrigatório.',
    'phone.numeric' => 'O campo número deve ser númerico.',
    'phone.digits' => 'O campo número deve ter exactamente 9 caracteres.',
    'location.required' => 'O campo location é obrigatório.',
    'location.max' => 'O campo location deve ter no máximo 255 caracteres.',
    'website.required' => 'O campo website é obrigatório.',
    'website.url' => 'O campo website deve ser uma url.',
  ];

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
        'name' => ['required', 'string', 'max:200', 'regex:/^[^\d]+$/'],
        'email' => ['required', 'email'],
        'phone' => ['required', 'numeric', 'digits:9'],
        'location' => ['required', 'string', 'max:255'],
        'website' => ['required', 'url']
      ], $this->customMessages);


      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $organization = new Organization();

    $organization->name = $data['name'];
    $organization->email = $data['email'];
    $organization->phone = $data['phone'];
    $organization->location = $data['location'];
    $organization->website = $data['website'];
    $saved = $organization->save();

    if ($saved) {
      return redirect()->back()->with('success', 'Esta instituicao foi guardada com sucesso!');
    } else {
      return redirect()->back()->with('error', 'Algo deu errado na criacao');
    }
  }

  /**
   * Show a specific resource from the Controller
   * @param int $id
   * @return View
   */

  public function show($id)
  {
    $organization = Organization::find($id);
    $managers = User::where('organization_id', $organization->id)->whereHas('roles', function ($query) {
      $query->where('name', 'gestor');
    })->get();

    return view('organization.single', compact('organization', 'managers'));
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
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:200', 'regex:/^[^\d]+$/'],
        'email' => ['required', 'email'],
        'phone' => ['required', 'numeric', 'digits:9'],
        'location' => ['required', 'string', 'max:255'],
        'website' => ['required', 'url']
      ], $this->customMessages += ['id' => 'Algo esta errado com o ID']);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $organization = Organization::find($data['id']);

    if (!$organization) {
      return redirect()->back()->with('warning', 'No organization found');
    }

    $organization->name = $data['name'];
    $organization->email = $data['email'];
    $organization->phone = $data['phone'];
    $organization->location = $data['location'];
    $organization->website = $data['website'];
    $updated = $organization->update();

    if ($updated) {
      return redirect()->back()->with('success', 'Esta instituicao foi guardada com sucesso!');
    } else {
      return redirect()->back()->with('error', 'Algo deu errado na criacao');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->with('warning', 'Algo de errado nao esta certo!');
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $id = $request->id;
    $organization = Organization::find($id);
    if ($organization) {
      $deleted = $organization->delete();
      if ($deleted) {
        return redirect()->back()->with('success', 'Instituicao apagada com sucesso!');
      } else {
        return redirect()->back()->with('warning', 'Algo de errado deu certo!');
      }
    }

    return redirect()->back()->with('error', 'Instituicao nao encontrada!');
  }
}
