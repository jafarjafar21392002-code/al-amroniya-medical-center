<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\SlotService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Get;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.calendar';

    public ?string $selectedDate = null;

    // =========================
    // EVENTS
    // =========================
    protected function getViewData(): array
    {
        return [
            'events' => Appointment::with('doctor')
                ->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'title' => $a->patient_name . ' (' . $a->doctor->name . ')',
                    'start' => $a->date . 'T' . $a->time,
                    'backgroundColor' => match ($a->status) {
                        'confirmed' => '#16a34a',
                        'pending' => '#f59e0b',
                        'canceled' => '#dc2626',
                        default => '#6b7280',
                    },
                ])
                ->values(),
        ];
    }

    // =========================
    // HEADER ACTION
    // =========================
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Appointment')
                ->modalHeading('New Appointment')

                ->form([

                    // DOCTOR
                    Select::make('doctor_id')
                        ->label('Doctor')
                        ->options(Doctor::pluck('name', 'id'))
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('date', null);
                            $set('time', null);
                        })
                        ->required(),

                    // PATIENT
                    TextInput::make('patient_name')
                        ->label('Patient Name')
                        ->required(),

                    TextInput::make('phone')
                        ->label('Phone')
                        ->required(),

                    // DATE
                    DatePicker::make('date')
                        ->label('Date')
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('time', null);
                        })
                        ->required(),

                    // TIME SLOTS (FIXED 100%)
                    Select::make('time')
                        ->label('Available Slots')
                        ->options(function (Get $get) {

                            $doctorId = $get('doctor_id');
                            $date = $get('date');

                            if (!$doctorId || !$date) {
                                return [];
                            }

                            return app(SlotService::class)->getSlots(
                                (int) $doctorId,
                                Carbon::parse($date)->format('Y-m-d')
                            );
                        })
                        ->live()
                        ->reactive()
                        ->required(),
                ])

                ->action(function (array $data) {

                    Appointment::create([
                        'doctor_id' => $data['doctor_id'],
                        'patient_name' => $data['patient_name'],
                        'phone' => $data['phone'],
                        'date' => Carbon::parse($data['date'])->format('Y-m-d'),
                        'time' => $data['time'],
                        'status' => 'pending',
                    ]);

                    Notification::make()
                        ->title('Appointment Created')
                        ->success()
                        ->send();
                }),
        ];
    }

    // =========================
    // OPEN MODAL
    // =========================
    public function openCreateModal($date): void
    {
        $this->selectedDate = $date;
        $this->mountAction('create');
    }
}
