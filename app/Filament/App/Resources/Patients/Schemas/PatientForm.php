<?php

namespace App\Filament\App\Resources\Patients\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('FirstName')->required(),
                TextInput::make('MiddleName'),
                TextInput::make('LastName')->required(),
                DateTimePicker::make('BirthDate'),
                TextInput::make('Weight')
                    ->numeric(),
                TextInput::make('MobileNo'),
                TextInput::make('Email')->email(),
                TextInput::make('OtherIdNumber'),
                Radio::make('Gender')->options([
                    'male' => 'Male', 'female' => 'Female', "others" => 'Others'
                ])->columns(3),
                TextArea::make('Address')->columnSpanFull(),
                FileUpload::make('Image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
            ])->columns(3);
    }
}
