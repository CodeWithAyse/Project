<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB; 
 
class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // Kullanıcıdan gelen verileri al
        $appointmentData = $request->only(['userId', 'polyclinicId', 'doctorId', 'appointmentDate', 'appointmentTime']);
        
        // Daha önce aynı doktor, poliklinik ve tarihte bir randevu var mı kontrol et
        $existingAppointment = Appointment::where([
            ['doctorId', '=', $appointmentData['doctorId']],
            ['polyclinicId', '=', $appointmentData['polyclinicId']],
            ['appointmentDate', '=', $appointmentData['appointmentDate']],
            ['appointmentTime', '=', $appointmentData['appointmentTime']],
        ])->first();

        // Eğer böyle bir randevu varsa, hata mesajı dön
        if ($existingAppointment) {
            return response()->json(['message' => 'Bu tarih ve saatte zaten bir randevu mevcut.'], 400);
        }

        // Yeni randevu oluştur
        $appointment = new Appointment();
        $appointment->userId = $appointmentData['userId'];  // Kullanıcı ID'si
        $appointment->polyclinicId = $appointmentData['polyclinicId'];  // Poliklinik ID
        $appointment->doctorId = $appointmentData['doctorId'];  // Hekim ID
        $appointment->appointmentDate = $appointmentData['appointmentDate'];  // Tarih
        $appointment->appointmentTime = $appointmentData['appointmentTime'];  // Saat

        // Randevuyu kaydet
        $appointment->save();

        return response()->json(['message' => 'Randevu başarıyla kaydedildi.'], 201);
    }

    // Tüm randevuları listele
    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments);
      
    }

    public function getAvailableTimes(Request $request)
{
 
    $doctorId = $request->doctorId; // Doktor ID'sini request'ten al
    $appointmentData=$request->appointmentDate;
    $appointments=DB::table('appointment')
            ->where('doctorId', $doctorId)
            ->where('appointmentDate',$appointmentData)
             ->pluck('appointmentTime')
             ->toArray();        

    return response()->json(['takenAppointments' => $appointments]);
}

}
