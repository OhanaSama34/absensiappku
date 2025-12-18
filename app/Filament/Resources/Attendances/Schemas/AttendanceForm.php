<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Forms\Form;
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
                DateTimePicker::make('check_in_time'),
                DateTimePicker::make('check_out_time'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),

                Map::make('location')
                    ->label('Location')
                    ->columnSpanFull()
                    // Basic Configuration
                    // ->defaultLocation(latitude: 40.4168, longitude: -3.7038)
                    // ->draggable(true)
                    // ->clickable(true) // click to move marker
                    // ->zoom(15)
                    // ->minZoom(0)
                    // ->maxZoom(28)
                    // ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                    // ->detectRetina(true)
                    ->liveLocation(true, true, 5000)
                    ->showMyLocationButton(true)
                    // ->boundaries(true, 49.5, -11, 61, 2) // Example for British Isles
                    ->rangeSelectField('distance')
                    ->afterStateUpdated(function ($set, ?array $state): void {
                        $set('latitude', $state['lat']);
                        $set('longitude', $state['lng']);
                        // $set('geojson', json_encode($state['geojson']));
                    }),

                Toggle::make('is_late')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
