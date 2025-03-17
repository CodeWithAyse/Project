<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Appointment extends Model
{
    use HasFactory;

    protected $table = 'Appointment'; 

    protected $fillable = [
        'userId',
        'doctorId',
        'polyclinicId',
        'appointmentDate',
        'appointmentTime',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function doctor()
    // {
    //     return $this->belongsTo(Doctor::class);
    // }

    // public function polyclinic()
    // {
    //     return $this->belongsTo(Polyclinic::class);
    // }
}