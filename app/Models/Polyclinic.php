<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Polyclinic extends Model
{
    use HasFactory;

    protected $table = 'Polyclinic'; 

    protected $fillable = [
        'name',
    ];
    protected $casts = [
        'appointmentDate' => 'datetime:Y-m-d',
    ];
}
