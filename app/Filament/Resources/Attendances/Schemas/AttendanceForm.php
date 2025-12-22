<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Dotswan\MapPicker\Fields\Map;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),
                TextInput::make('user_name')
                    ->label('User Name')
                    ->readOnly()
                    ->default(fn() => Auth::user()->name),
                Select::make('schedule_id')
                    ->relationship('schedule', 'name')
                    ->required(),
                DatePicker::make('attendance_date')
                    ->required(),
                
            DateTimePicker::make('check_in_time') 
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    $scheduleId = $get('schedule_id');

                    if (! $state || ! $scheduleId) {
                        $set('is_late', false);
                        return;
                    }

                    $schedule = Schedule::find($scheduleId);

                    if (! $schedule || ! $schedule->start_time) {
                        $set('is_late', false);
                        return;
                    }

                    $checkIn   = Carbon::parse($state);
                    $startTime = Carbon::parse($schedule->start_time)
                        ->setDate(
                            $checkIn->year,
                            $checkIn->month,
                            $checkIn->day
                        );

                    $set('is_late', $checkIn->greaterThan($startTime));
                }),
                DateTimePicker::make('check_out_time'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),

                Map::make('location')
                    ->label('Location')
                    ->columnSpanFull()

                    ->liveLocation(true, true, 5000)
                    ->showMyLocationButton(true)
  
                    ->rangeSelectField('distance')
                    ->afterStateUpdated(function ($set, ?array $state): void {
                        $set('latitude', $state['lat']);
                        $set('longitude', $state['lng']);
 
                    }),

                Toggle::make('is_late')
                ->label('Late')
                ->disabled()
                ->dehydrated(),
                
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
