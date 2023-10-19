<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
  use HasFactory;

  protected $table = "events";
  
  // Get Participants Function
  public function participants(): BelongsToMany
  {
    return $this->belongsToMany(Participant::class, 'participant_event');
  }

  // Get Schedules Function
  public function schedules(): HasMany
  {
    return $this->hasMany(Schedule::class);
  }
}
