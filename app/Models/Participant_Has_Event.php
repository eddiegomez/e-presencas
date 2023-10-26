<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant_Has_Event extends Model
{
  use HasFactory;

  protected $table = 'participant_event';

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
}
