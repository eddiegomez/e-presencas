<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Models\Participant;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect()->route('login');
});

Route::get('/showBusinessCard/{hash}', [ParticipantController::class, 'showBusinessCard'])->name('showBusinessCard');

Route::middleware(['auth', 'web'])->group(function () {
  Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

  // ManagerController Routes
  Route::get('/managers', [ManagerController::class, 'index'])->name('managers.list');
  Route::post('/manager/create', [ManagerController::class, 'store'])->name('manager.store');
  Route::post('/manager/update', [ManagerController::class, 'update'])->name('manager.update');
  Route::post('/manager/destroy', [ManagerController::class, 'destroy'])->name('manager.destroy');
  Route::post('/manager/changePwd', [ManagerController::class, 'changePwd'])->name('manager.changePwd');

  // AdminsController Routes
  Route::get('/admin', [AdminController::class, 'index'])->name('admin.list');
  Route::post('/admin/create', [AdminController::class, 'store'])->name('admin.store');
  Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');
  Route::post('/admin/delete', [AdminController::class, 'destroy'])->name('admin.destroy');

  // OrganizationController Routes
  Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.list');
  Route::get('/organization/{id}', [OrganizationController::class, 'show'])->name('organization.show');
  Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');
  Route::post('/organization/update', [OrganizationController::class, 'update'])->name('organization.update');
  Route::post('/organization/destroy', [OrganizationController::class, 'destroy'])->name('organization.destroy');

  // Get all events Controller
  Route::get('/events', [EventController::class, 'index'])->name('event.list');
  // Create a new event Controller
  Route::post('/event', [EventController::class, 'store'])->name('event.store');
  // Get a single event Controller
  Route::get('/event/{id}', [EventController::class, 'show'])->name('event.show');
  // Edit Event Controller
  Route::post('/event/{id}', [EventController::class, 'update'])->name('event.update');
  // Delete Event Controller
  Route::post('/event/delete/{id}', [EventController::class, 'destroy'])->name('event.destroy');

  // Create Schedule
  Route::post('/schedule/store/{id}', [EventController::class, 'createSchedule'])->name('schedule.create');
  // Remove Schedule
  Route::post('/schedule/remove', [EventController::class, 'removeSchedule'])->name('schedule.remove');

  // Participants Controllers

  // Get all participants controller
  Route::get('/participants', [ParticipantController::class, 'index'])->name('participant.index');
  // Create a new Participant Controller
  Route::post('/participant/create', [ParticipantController::class, 'store'])->name('participant.store');
  // Get a single Participant Controller
  Route::get('/participant/{id}', [ParticipantController::class, 'show'])->name('participant.show');
  // Edit a Participant Controller
  Route::post('/participant/{id}/edit', [ParticipantController::class, 'update'])->name('participant.update');
  // Delete a participant Controller
  Route::post('/participant/{id}/delete', [ParticipantController::class, 'destroy'])->name('participant.destroy');


  // Create a new Invite route
  Route::post('/inviteParticipant', [InviteController::class, 'store'])->name('invite.store');
  // Delete a new Invite controller
  Route::post('/invite/destroy', [InviteController::class, 'destroy'])->name('invite.delete');
  //Update participant type
  Route::post('/invite/update/{eventId}', [InviteController::class, 'update'])->name('invite.update');
  // Confirmar Entrada Controllers
  Route::put('/staff/confirm/entrance/{event}/{participant}/{status}', [InviteController::class, 'confirmEntrance'])->name('participant.entrance');
  //Confirm Participant Entrance
  Route::post('/confirm/entrance/{encryptedevent}/{encryptedparticipant}', [InviteController::class, 'confirmEntrancePost'])->name('confirmEntranceUpdate');
  // Remove Participant from event
  Route::post('/removeParticipantFromEvent', [InviteController::class, 'removeParticipantFromEvent'])->name('removeParticipantFromEvent');
  // Change Participation Type
  Route::post('/changeParticipantType', [InviteController::class, 'changeParticipantType'])->name('changeParticipantType');
  // Attend to event
  Route::get('/openRoom/{id}', [InviteController::class, 'openRoom'])->name('participant.openRoom');

  // Get all staff members Controller
  Route::get('/protocolos', [StaffController::class, 'index'])->name('protocolos.index');
  // Create a new staff member Controller
  Route::post('/protocolo/create', [StaffController::class, 'store'])->name('staff.store');
  // Update a certain staff member
  Route::post('/protocolo/edit', [StaffController::class, 'update'])->name('staff.update');
  // Delete a Staff Member Controller
  Route::delete('/protocolo/delete', [StaffController::class, 'destroy'])->name('staff.delete');
  // Add staff to event
  Route::post('/staff.add', [EventController::class, 'addStaff'])->name('staff.add');
  // Remove staff from event
  Route::post('/removeStaffFromEvent', [StaffController::class, 'removeStaffFromEvent'])->name('removeStaffFromEvent');
});



// ----------------------------------------------------------------
//Unprotected Routes

// Confirmar registro de protocolo!
Route::get('/protocolo/{encryptedId}/confirmation/', [StaffController::class, 'emailConfirmation'])->name('protocolo.confirmation');

Route::post('/protocolo/{encryptedId}/confirmation', [StaffController::class, 'emailConfirmationPost'])->name('protocolo.confirmRegister');


// Confirm Presence Controller
Route::get('/invite/accept/{encryptedevent}/{encryptedparticipant}', [InviteController::class, 'acceptInvite'])->name('invite.acceptInvite');
Route::post('/send-email', [ContactController::class, 'sendEmail']);

Route::get('lang/{lang}', function ($lang) {
  // Set the locale in the session
  if (in_array($lang, ['en', 'pt'])) { // Add your available languages
    Session::put('locale', $lang);
    App::setLocale($lang);
  }
  return redirect()->back();
})->name('language.switch');

// Get all staff members Controller
Route::get('/evento/{encryptedEvent}', [EventController::class, 'applyEvent'])->name('evento');
//Self register to event
Route::post('/participants', [ParticipantController::class, 'register'])->name('participants.register');
// Attend to event
Route::get('/attend/{id}', [InviteController::class, 'attend'])->name('participant.attend');
//Confirm Participant Entrance
Route::post('/entrance/{encryptedevent}', [InviteController::class, 'entrance'])->name('entrance');

Route::get('/participants/share-vcard', [ParticipantController::class, 'shareVFCard'])
    ->name('participants.shareVCard');


Auth::routes();
