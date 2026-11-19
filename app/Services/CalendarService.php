<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class CalendarService
{
    public function getDayStatus(int $doctorId, string $date): array
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (! $schedule) {
            return [
                'status' => 'unavailable',
                'color' => '#9ca3af',
            ];
        }

        $totalSlots = $this->countSlots($schedule);

        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->count();

        $ratio = $totalSlots > 0 ? $bookedSlots / $totalSlots : 1;

        return match (true) {
            $ratio >= 1 => ['status' => 'full', 'color' => '#dc2626'],
            $ratio >= 0.7 => ['status' => 'busy', 'color' => '#f59e0b'],
            default => ['status' => 'available', 'color' => '#16a34a'],
        };
    }

    private function countSlots(DoctorSchedule $schedule): int
    {
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);

        return (int) ceil($start->diffInMinutes($end) / $schedule->slot_duration);
    }
}
