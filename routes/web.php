<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PolyclinicController;

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('app');
});
// Route::get('/{any}', function () {
//     return view('app'); // Vue.js ana şablonunu döndür
// })->where('any', '.*');


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/doctor', [DoctorController::class, 'index']);
Route::get('/polyclinic', [PolyclinicController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/available-times', [AppointmentController::class, 'getAvailableTimes']);

