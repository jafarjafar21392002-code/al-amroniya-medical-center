<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        return view('appointment', compact('doctor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'patient_name' => 'required',
            'phone' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
        ]);

        return redirect('/')->with('success', 'Appointment booked successfully!');
    }
}
