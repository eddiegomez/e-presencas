<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invites;
use App\Models\Organization;
use App\Models\Participant;
use App\Models\Participant_Has_Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $events = Event::all()->count();
    $participants = Participant::all()->count();
    $organizations = Organization::all()->count();
    $users = User::all()->count();

    return view('dashboard', compact('events', 'participants', 'organizations', 'users'));
  }
}
