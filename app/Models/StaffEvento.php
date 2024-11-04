<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffEvento extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "staff_evento";

    protected $fillable = [
        'staff_id',
        'evento_id'
    ];
}
