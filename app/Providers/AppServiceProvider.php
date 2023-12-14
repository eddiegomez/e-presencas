<?php

namespace App\Providers;

use App\Services\InviteService;
use Illuminate\Support\ServiceProvider;
use App\Services\ParticipantService;

class AppServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->bind(ParticipantService::class, function () {
      return new ParticipantService();
    });

    $this->app->bind(InviteService::class, function ($app) {
      $participantService = $app->make(ParticipantService::class);
      return new InviteService($participantService);
    });
  }
}
