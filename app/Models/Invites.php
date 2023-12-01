<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invites extends Model
{
  use HasFactory;

  protected $table = 'invites';

  protected $fillable = [
    'participant_id',
    'event_id',
    'participant_type_id',
    'qr_url',
    'status'
  ];
  protected $attributes = [
    'status' => 'Em espera',
  ];

  public $timestamps = true;

  public function event($event): HasOne
  {
    return $this->hasOne(Event::class, 'id', 'event_id');
  }

  public function participantType($participantType): HasOne
  {
    return $this->hasOne(ParticipantType::class, 'id', 'participant_type_id');
  }
}
