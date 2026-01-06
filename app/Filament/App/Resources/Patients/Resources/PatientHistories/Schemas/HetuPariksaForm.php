<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;

class HetuPariksaForm
{
    public static function configure()
    {

        return
            Group::make()
                ->relationship('hetuPariksa')
                ->schema([
                    ViewField::make('Responses')
                        ->view('hetu-form')
                        ->hiddenLabel(),
                ]);
    }

    protected static function getQuestionRow(int $number, string $questionText, array $inputs): Component
    {
        return Grid::make(12)
            ->schema([
                Group::make()
                    ->columnSpan(6)
                    ->schema([
                        Placeholder::make("lbl_{$number}")
                            ->hiddenLabel()
                            ->content(new HtmlString("<strong>{$questionText}</strong>")),
                        Group::make()->schema($inputs)
                    ]),

                // Dosha Checkboxes (Keys: q1_vat, q1_pit, etc)
                Group::make()
                    ->columnSpan(3)
                    ->schema([
                        Checkbox::make("q{$number}_vat")->label('VAT'),
                        Checkbox::make("q{$number}_pit")->label('PIT'),
                        Checkbox::make("q{$number}_kuf")->label('KUF'),
                    ]),

                // Status Radio (Key: q1_status)
                Radio::make("q{$number}_status")
                    ->hiddenLabel()
                    ->options(['HitKar' => 'હિતકર', 'AhitKar' => 'અહિતકર'])
                    ->columnSpan(3),
            ])
            ->extraAttributes(['class' => 'border-b border-gray-200 py-4']);
    }

    protected static function calculateTotals(Get $get): array
    {
        $totals = ['VAT' => 0, 'PIT' => 0, 'KUF' => 0, 'HitKar' => 0, 'AhitKar' => 0];

        for ($i = 1; $i <= 35; $i++) {
            if ($get("q{$i}_vat")) $totals['VAT']++;
            if ($get("q{$i}_pit")) $totals['PIT']++;
            if ($get("q{$i}_kuf")) $totals['KUF']++;

            $status = $get("q{$i}_status");
            if ($status === 'HitKar') $totals['HitKar']++;
            if ($status === 'AhitKar') $totals['AhitKar']++;
        }

        return $totals;
    }

}
