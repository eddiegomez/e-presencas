<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
  use HasFactory;

  protected $table = 'schedules';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    "name",
    "pdf_url",
    "date",
    "event_id"
  ];

  // Get Event 
  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'invites');
  }
}
