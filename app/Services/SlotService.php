<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class SlotService
{
    public function getSlots(int $doctorId, string $date): array
    {
        $date = Carbon::parse($date);

        // 🔥 مهم: نثبت UTC day mapping
        $dayOfWeek = $date->dayOfWeek;

        logger()->info('DEBUG SLOT INPUT', [
            'doctor_id' => $doctorId,
            'date' => $date->toDateString(),
            'dayOfWeek' => $dayOfWeek,
        ]);

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            logger()->info('NO SCHEDULE FOUND');
            return [];
        }

        $start = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $end   = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

        $booked = Appointment::where('doctor_id', $doctorId)
            ->where('date', $date->format('Y-m-d'))
            ->pluck('time')
            ->toArray();

        $slots = [];

        while ($start < $end) {

            $time = $start->format('H:i:s');

            if (!in_array($time, $booked)) {
                $slots[$time] = $start->format('H:i');
            }

            $start->addMinutes($schedule->slot_duration);
        }

        logger()->info('SLOTS GENERATED', $slots);

        return $slots;
    }
}
