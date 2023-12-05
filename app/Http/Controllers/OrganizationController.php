<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $organizations = Organization::all();

    return response(view('organization.list', compact('organizations')));
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
        'email' => ['required', 'email'],
        'phone' => ['required', 'string', 'min:9', 'max:9'],
        'location' => ['required', 'string'],
        'website' => ['required', 'string']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->with('Warning', 'Algo de errado nao esta certo!');
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
        'name' => ['required', 'string'],
        'email' => ['required', 'email'],
        'phone' => ['required', 'string', 'min:9', 'max:9'],
        'location' => ['required', 'string'],
        'website' => ['required', 'string']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->with('Warning', 'Algo de errado nao esta certo!');
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
