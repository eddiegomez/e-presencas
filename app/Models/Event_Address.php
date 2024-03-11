<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event_Address extends Model
{
  use HasFactory;
  protected $table = "event_address";

  public $fillable = [
    "event_id",
    "address_id"
  ];
}
