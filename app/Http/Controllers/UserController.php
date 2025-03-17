<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Polyclinic;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'tc_no'=> 'required|numeric|digits:11|unique:users|regex:/^[1-9]{1}[0-9]{10}$/',
            'name' => 'required',
            'surname'=> 'required',
            'gender'=>'required',
            'birth_date'=>'required|date',
            'phone'=>'required|numeric',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',]);
        
        $user=User::create([
            'tc_no'=>$request->tc_no,
            'name'=>$request->name,
            'surname'=>$request->surname,
            'gender'=>$request->gender,
            'birth_date'=>$request->birth_date,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        
    }
    public function login(Request $request)
    {
        $user=User::where('tc_no',$request->tc_no)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }


        return response()->json(['message' => 'Login successful',
        'userId' => $user->id // Kullanıcı ID'sini döndür
        

    ]);
    }
}
