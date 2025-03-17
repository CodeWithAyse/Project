<?php

namespace App\Http\Controllers;
use App\Models\Polyclinic; 
use Illuminate\Http\Request;

class PolyclinicController extends Controller
{
    public function index($id="")
    {
        if( $id == "" ) 
        $polyclinic=Polyclinic::all();
    else
        $polyclinic=DB::table('polyclinic')
            ->join('doctor', 'doctor.polyclinicId', '=', 'polyclinic.id')
             ->where('polyclinic.id', '=', $id)
             ->get(['polyclinic.id','polyclinic.isim']);        
            return response()->json($polyclinic);
    }
}
