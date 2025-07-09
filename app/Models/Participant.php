<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Participant extends Model
{
  use HasFactory, SoftDeletes, Notifiable;

  protected $table = 'participants';

  public $fillable = [
    'name',
    'last_name',
    'description',
    'email',
    'phone_number',
    'degree',
    'upload',
    'organization_id'
  ];

  public $timestamps = true;

  // Get Events function
  public function events(): BelongsToMany
  {
    return $this->belongsToMany(Event::class, 'invites')->withPivot('status', 'participant_type_id');
  }

  /**
   * Check if participant has Event
   * @param int $event
   * @return mixed
   */
  public function hasEvent($event)
  {
    return $this->events->contains('id', $event);
  }

  public function hasRelationWithOrganization($organization_id)
  {
    return $this->events->contains('organization_id', $organization_id);
  }
}
