<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
  use HasFactory;

  protected $table = 'schedule';

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

  public $timestamps = true;

  // Get Event 
  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'invites');
  }
}
