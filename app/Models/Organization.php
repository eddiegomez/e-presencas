<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
  use HasFactory;

  protected $table = 'organization';

  protected $fillable = [
    "name",
    "email",
    "phone",
    "location",
    "website"
  ];

  public $timestamps = true;

  //Get all users related to this organization
  public function Users(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
