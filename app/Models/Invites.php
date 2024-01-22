<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invites extends Model
{
  use HasFactory;

  /**
   * The primary key columns for the model.
   *
   * @var array
   */
  protected $primaryKey = ['participant_id', 'event_id'];

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

  public $incrementing = false;

  public $timestamps = true;

  public function event(): HasOne
  {
    return $this->hasOne(Event::class, 'id', 'event_id');
  }

  public function participant(): HasOne
  {
    return $this->hasOne(Participant::class, 'id', 'participant_id');
  }

  public function participantType($participantType): HasOne
  {
    return $this->hasOne(ParticipantType::class, 'id', 'participant_type_id');
  }
}
