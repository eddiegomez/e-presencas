<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Participant extends Model
{
  use HasFactory;

  protected $table = "participants";

  // Get Events function
  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'participant_event');
  }

  //Check if it has single event
  public function hasEvent($event){
    return $this->events->contains('id', $event);
  }
  
  
}
