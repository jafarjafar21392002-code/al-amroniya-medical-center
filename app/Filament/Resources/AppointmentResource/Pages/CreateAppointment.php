<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $exists = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('date', $data['date'])
            ->where('time', $data['time'])
            ->exists();

        if ($exists) {
            // 🔥 هذا هو المهم
            throw ValidationException::withMessages([
                'time' => '⚠️ هذا الموعد غير متاح - الطبيب مشغول في هذا الوقت',
            ]);
        }
    }
}
