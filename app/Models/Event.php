<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = "events";

  protected $fillable = [
    'name',
    'banner_url',
    'start_date',
    'end_date',
    'start_time',
    'end_time'
  ];

  public $timestamps = true;
  
  // Get Participants Function
  public function participants(): BelongsToMany
  {
    return $this->belongsToMany(Participant::class, 'participant_event')->withPivot('qr_url', 'status');
  }

  // Check if it has participant by ID
  public function hasParticipant($participant): HasOneThrough
  {
    return $this->hasOneThrough(Participant::class, 'participant_event');
  }

  // Get Schedules Function
  public function schedules(): HasMany
  {
    return $this->hasMany(Schedule::class);
  }

  // Get the event address
  public function addresses(): BelongsToMany
  {
    return $this->belongsToMany(Address::class, 'event_address');
  }
}
