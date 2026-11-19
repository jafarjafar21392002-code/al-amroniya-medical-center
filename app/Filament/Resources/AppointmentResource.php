<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Select::make('doctor_id')
                ->label('Doctor')
                ->relationship('doctor', 'name')
                ->required()
                ->live(),

            TextInput::make('patient_name')
                ->required(),

            TextInput::make('phone')
                ->required(),

            DatePicker::make('date')
                ->required()
                ->live(),

            Select::make('time')
                ->label('Available Slots')
                ->options(function (Get $get) {

                    if (! $get('doctor_id') || ! $get('date')) {
                        return [];
                    }

                    $doctorId = $get('doctor_id');
                    $date = $get('date');

                    $dayOfWeek = Carbon::parse($date)->dayOfWeek;

                    $schedule = DoctorSchedule::where('doctor_id', $doctorId)
                        ->where('day_of_week', $dayOfWeek)
                        ->first();

                    if (! $schedule) {
                        return [];
                    }

                    $start = Carbon::parse($schedule->start_time);
                    $end = Carbon::parse($schedule->end_time);
                    $slotDuration = $schedule->slot_duration;

                    $booked = Appointment::where('doctor_id', $doctorId)
                        ->where('date', $date)
                        ->pluck('time')
                        ->map(fn ($t) => substr($t, 0, 5))
                        ->toArray();

                    $slots = [];

                    while ($start < $end) {

                        $time = $start->format('H:i');

                        if (! in_array($time, $booked)) {
                            $slots[$time] = $time;
                        }

                        $start->addMinutes($slotDuration);
                    }

                    return $slots;
                })
                ->required()
                ->live()
                ->native(false)
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('time', $state);
                }),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'canceled' => 'Canceled',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('doctor.name')->label('Doctor'),

            TextColumn::make('patient_name'),

            TextColumn::make('phone'),

            TextColumn::make('date'),

            TextColumn::make('time'),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'confirmed' => 'success',
                    'canceled' => 'danger',
                }),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
