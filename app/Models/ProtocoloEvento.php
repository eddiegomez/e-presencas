<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocoloEvento extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "protocolo_evento";

    protected $fillable = [
        'protocolo_id',
        'evento_id'
    ];
}
