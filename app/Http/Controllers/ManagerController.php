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
    $search = $request->search;

    $gestores = User::role('gestor')->when($search, function ($query) use ($search) {
      $query->where('name', 'like', '%' . $search . '%');
    })->get();

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
        'phone' => ['required', 'max:9', 'min:8', 'string'],
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
}
