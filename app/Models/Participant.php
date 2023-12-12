<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'participants';

  public $fillable = [
    'name',
    'description',
    'email',
    'phone_number',
    'degree'
  ];

  public $timestamps = true;

  // Get Events function
  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'invites')->withPivot('qr_url', 'status', 'participant_type_id');
  }

  //Check if it has single event by Id
  public function hasEvent($event)
  {
    return $this->events->contains('id', $event);
  }
}
