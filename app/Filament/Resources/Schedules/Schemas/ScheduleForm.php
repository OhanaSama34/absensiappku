<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TimePickerField::make('start_time')
                    ->required(),
                TimePickerField::make('end_time')
                    ->required(),
                TextInput::make('grace_period_minutes')
                    ->required()
                    ->numeric()
                    ->default(15),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
