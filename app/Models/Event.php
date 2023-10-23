<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = "events";

  protected $fillable = [
    'name',
    'date',
    'banner_url'
  ];

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
