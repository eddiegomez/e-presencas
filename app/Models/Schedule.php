<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
  use HasFactory;

  protected $table = 'schedules';

  // Get Event 
  public function events() : BelongsToMany{
    return $this->belongsToMany(Event::class, 'event_schedule');
  }

}
