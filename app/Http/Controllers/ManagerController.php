<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ManagerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return View
   */
  public function index(Request $request)
  {
    $gestores = User::role('gestor')->paginate(12);
    $organizations = Organization::all();

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
        'name' => ['required', 'max:255', 'string'],
        'email' => ['required', 'max:255', 'email'],
        'phone' => ['required', 'max:9', 'min:9', 'string'],
        'organization' => ['required', 'integer']
      ]);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    } catch (\Throwable $th) {
      throw $th;
    }

    $data = $request->all();
    $gestor = new User();
    $organization = Organization::find($data['organization']);


    $gestor->name = $data['name'];
    $gestor->email = $data['email'];
    $gestor->phone = $data['phone'];
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
    try {
      $validator = Validator::make($request->all(), [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email'],
        'phone' => ['required', 'numeric'],
        'organization' => ['required', 'integer']
      ]);

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
