<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Schemas;

use App\Models\Anupana;
use App\Models\MedicineForm;
use App\Models\Panchakarma;
use App\Models\TimeOfAdministration;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PatientHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')->schema([
                    Tabs\Tab::make('Info')->schema([

                        ModalTableSelect::make("diseases")
                            ->multiple()
                            ->tableConfiguration(DiseaseTable::class)
                            ->relationship("diseases", "Name")
                            ->live(),

                        ModalTableSelect::make("symptoms")
                            ->multiple()
                            ->tableConfiguration(SymptomTable::class)
                            ->relationship("symptoms", "Name")
                            ->key(
                                fn(Get $get) => "symptoms-" .
                                    md5(json_encode($get("diseases"))),
                            )
                            ->tableArguments(
                                fn(Get $get): array => [
                                    "diseases" => $get("diseases"),
                                ],
                            ),

                        Select::make('modern_symptoms')->relationship('modernSymptoms', 'Name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('Name')->required(),
                                Textarea::make('Description'),
                            ]),

                        Repeater::make('Prescriptions')->relationship('prescriptions')->table([
                            Repeater\TableColumn::make('Medicine'),
                            Repeater\TableColumn::make('Dose'),
                            Repeater\TableColumn::make('TimeOfAdministration'),
                            Repeater\TableColumn::make('Anupana'),
                            Repeater\TableColumn::make('MedicineFormName'),
                            Repeater\TableColumn::make('Amount'),

                        ])->schema([
                            Select::make('MedicineId')->searchable()->preload()->relationship('medicine', 'Name')->columnSpanFull()->required(),
                            TextInput::make('Dose'),
                            TextInput::make('TimeOfAdministration')->datalist(fn() => TimeOfAdministration::all()->pluck('Name', 'Id')),
                            TextInput::make('Anupana')->datalist(fn() => Anupana::all()->pluck('Name', 'Id')),
                            TextInput::make('MedicineFormName')->datalist(fn() => MedicineForm::all()->pluck('Name', 'Id')),
                            TextInput::make('Amount')->numeric(),
                        ])->compact()->columnSpanFull()->cloneable(),

                        TextInput::make('ConsultationFee')
                            ->required()
                            ->numeric(),
                        TextInput::make('MedicinesFee')
                            ->required()
                            ->numeric(),

                        DateTimePicker::make('NextAppointmentDate'),
                        Textarea::make('Remark')
                            ->columnSpanFull(),
                        Textarea::make('Note')
                            ->columnSpanFull(),
                    ])->columns(3),
                    Tabs\Tab::make("Vital")->schema([
                        Fieldset::make("Vitals")
                            ->relationship("vital")
                            ->schema([
                                TextInput::make("BodyTemperature")->default(''),
                                TextInput::make("PluseRate")->label('PulseRate')->default(''),
                                TextInput::make("RespirationRate")->default(''),
                                TextInput::make("BloodPressure")->default(''),
                                TextInput::make("Spo2")->default(''),
                                TextInput::make("DiabetesCount")->default(''),
                            ]),
                    ]),
                    Tabs\Tab::make("Women History")->schema([
                        Fieldset::make("Women History")
                            ->relationship("womenHistory")
                            ->schema([
                                Section::make('General Assessment')
                                    ->schema([
                                        Textarea::make('Chief_complaint')
                                            ->columnSpanFull()
                                            ->rows(2),

                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('Bp')
                                                    ->label('Blood Pressure')
                                                    ->placeholder('120/80')
                                                    ->suffix('mmHg'),
                                                TextInput::make('Pulse')
                                                    ->numeric()
                                                    ->suffix('bpm'),
                                                TextInput::make('Weight')
                                                    ->numeric()
                                                    ->suffix('kg'),
                                            ]),
                                    ]),

                                // 2. Menstrual History
                                Section::make('Menstrual History')
                                    ->columns(3)
                                    ->schema([
                                        DatePicker::make('First_menstrual_period')
                                            ->label('Menarche Date')
                                            ->maxDate(now()),
                                        DatePicker::make('Last_menstrual_period')
                                            ->label('LMP')
                                            ->maxDate(now()),
                                        TextInput::make('Duration')
                                            ->numeric()
                                            ->suffix('Days'),

                                        Select::make('Regular_irregular')
                                            ->label('Cycle Regularity')
                                            ->options([
                                                'Regular' => 'Regular',
                                                'Irregular' => 'Irregular',
                                            ]),

                                        Select::make('Painful_painless')
                                            ->label('Dysmenorrhea')
                                            ->options([
                                                'Painless' => 'Painless',
                                                'Painful' => 'Painful',
                                            ]),

                                        Select::make('Scanty_moderate_excessive')
                                            ->label('Flow Volume')
                                            ->options([
                                                'Scanty' => 'Scanty',
                                                'Moderate' => 'Moderate',
                                                'Excessive' => 'Excessive',
                                            ]),

                                        TextInput::make('Pads_used_per_day')
                                            ->label('Pads per Day')
                                            ->numeric(),
                                    ]),

                                // 3. Obstetric History (GPLAD)
                                Section::make('Obstetric History')
                                    ->description('Gravida, Parity, Live, Abortion, Dead')
                                    ->columns(5)
                                    ->schema([
                                        TextInput::make('Gravida')->numeric(),
                                        TextInput::make('Parity')->numeric(),
                                        TextInput::make('Abortion')->numeric(),
                                        TextInput::make('Live')->numeric(),
                                        TextInput::make('Dead')->numeric(),

                                        DatePicker::make('Last_delivery')
                                            ->label('Date of Last Delivery')
                                            ->maxDate(now())
                                            ->columnSpan(2), // Spans 2 columns for better look

                                        DatePicker::make('Expected_delivery_date')
                                            ->label('EDD')
                                            ->minDate(now())
                                            ->columnSpan(3),
                                    ]),

                                // 4. Delivery History Details
                                Section::make('Delivery Mode & History')
                                    ->collapsible()
                                    ->collapsed() // Keep it closed by default to save space
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('Full_term_yes_no')
                                                    ->label('Full Term?')
                                                    ->options(['Yes' => 'Yes', 'No' => 'No']),
                                                Select::make('Pre_term_yes_no')
                                                    ->label('Pre Term?')
                                                    ->options(['Yes' => 'Yes', 'No' => 'No']),
                                                Select::make('Lower_segment_yes_no')
                                                    ->label('LSCS?')
                                                    ->options(['Yes' => 'Yes', 'No' => 'No']),
                                                Select::make('Forcep_yes_no')
                                                    ->label('Forceps Used?')
                                                    ->options(['Yes' => 'Yes', 'No' => 'No']),
                                                Select::make('Vacum_yes_no')
                                                    ->label('Vacuum Used?')
                                                    ->options(['Yes' => 'Yes', 'No' => 'No']),
                                            ]),
                                    ]),

                                // 5. Personal History
                                Section::make('Personal History')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('Appetizer_regular_irregular')
                                            ->label('Appetite')
                                            ->options(['Regular' => 'Regular', 'Irregular' => 'Irregular']),
                                        Select::make('Sleep_regular_irregular')
                                            ->label('Sleep')
                                            ->options(['Regular' => 'Regular', 'Irregular' => 'Irregular']),
                                        Select::make('Stool_regular_irregular')
                                            ->label('Bowel Habit')
                                            ->options(['Regular' => 'Regular', 'Irregular' => 'Irregular']),
                                        Select::make('Urine_normal_abnormal')
                                            ->label('Micturition')
                                            ->options(['Normal' => 'Normal', 'Abnormal' => 'Abnormal']),

                                        Textarea::make('Coital_history')->rows(2),
                                        Textarea::make('Contraceptive_history')->rows(2),
                                    ]),

                                // 6. Examination
                                Section::make('Examination')
                                    ->schema([
                                        Textarea::make('Local_examination')
                                            ->label('Local Exam')
                                            ->rows(3),

                                        Grid::make(2)
                                            ->schema([
                                                Textarea::make('P_s')
                                                    ->label('Per Speculum (P/S)'),
                                                Textarea::make('P_v')
                                                    ->label('Per Vaginum (P/V)'),
                                            ]),
                                    ]),

                                // 7. Investigations & Other
                                Section::make('Conclusion')
                                    ->schema([
                                        Textarea::make('Investigations')
                                            ->rows(3),
                                        Textarea::make('Other')
                                            ->label('Other Notes')
                                            ->rows(2),
                                    ]),
                            ]),
                    ]),
                    Tabs\Tab::make("Panchakarma")
                        ->schema(
                            [
                                Repeater::make('panchakarmas')
                                    ->relationship('patientHistoryPanchakarmas')
                                    ->label('Panchakarma')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->table([
                                        Repeater\TableColumn::make('Panchakarma'),
                                        Repeater\TableColumn::make('Detail')
                                    ])
                                    ->reorderable(false) // Important to keep the list fixed
                                    ->schema([
                                        // 2. The "Label" Field (The Panchakarma Type)
                                        Select::make('PanchakarmaId')
                                            ->label('Panchakarma')
                                            ->options(Panchakarma::pluck('Name', 'Id'))
                                            ->disabled()
                                            ->dehydrated(),

                                        // 3. The Editable Field
                                        TextInput::make('Detail'),
                                    ])
                                    ->afterStateHydrated(function (Repeater $component, ?array $state) {
                                        // Get all master records
                                        $masters = Panchakarma::all();

                                        // Current DB data (or empty array)
                                        $existing = collect($state ?? []);

                                        $items = [];

                                        foreach ($masters as $master) {
                                            // Check if we already have a saved row for this Panchakarma
                                            // Ensure you match the type (string/int) correctly
                                            $found = $existing->firstWhere('PanchakarmaId', $master->Id);

                                            if ($found) {
                                                // If found, use the existing data.
                                                // CRITICAL: This includes the Pivot Table's Primary Key ('id'),
                                                // which tells Filament to UPDATE this row instead of creating a new one.
                                                $items[] = $found;
                                            } else {
                                                // If not found, create a "stub" for the form
                                                $items[] = [
                                                    'PanchakarmaId' => $master->Id,
                                                    'Detail' => null,
                                                    // No 'id' here, so Filament will CREATE this row on save
                                                ];
                                            }
                                        }

                                        // Force the repeater to use our merged list
                                        $component->state($items);
                                    })
                            ]
                        )
                    ,
                    self::rogaPariska(),

                    Tabs\Tab::make('HetuPariksa')->schema([
                        HetuPariksaForm::configure($schema)
                    ]),

                ])->columnSpanFull()
            ]);
    }

    public static function rogaPariska()
    {

        return Tabs\Tab::make('RogaPariska')->schema([
            Group::make()->relationship('rogaPariksa')->schema([

                Section::make('1) Dhosha')
                    ->description('Select the applicable Dhoshas')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Checkbox::make('Vat')->label('Vat'),
                                Checkbox::make('Pit')->label('Pit'),
                                Checkbox::make('Kaf')->label('Kaf'),
                            ]),
                    ]),

                // 2. Dooshya (Grouped by Sub-categories)
                Section::make('2) Dooshya')
                    ->schema([
                        Fieldset::make('Dhatu')
                            ->schema([
                                Checkbox::make('Rasa'),
                                Checkbox::make('Rakta'),
                                Checkbox::make('Mansa'),
                                Checkbox::make('Meda'),
                                Checkbox::make('Asthi'),
                                Checkbox::make('Majja'),
                                Checkbox::make('Shukra'),
                            ])->columns(4),

                        Fieldset::make('Upadhatu')
                            ->schema([
                                Checkbox::make('Stanya'),
                                Checkbox::make('Raja'),
                                Checkbox::make('Kandara'),
                                Checkbox::make('Sira'),
                                Checkbox::make('Dhamani'),
                                Checkbox::make('Twacha'),
                                Checkbox::make('Snau'),
                            ])->columns(4),

                        Fieldset::make('Mala')
                            ->schema([
                                Checkbox::make('Poorisha'),
                                Checkbox::make('Mootra'),
                                Checkbox::make('Sweda'),
                                Checkbox::make('Kapha'), // Note: Specific Mala Kapha
                                Checkbox::make('Pitta'), // Note: Specific Mala Pitta
                                Checkbox::make('Khamala'),
                                Checkbox::make('Kesha'),
                                Checkbox::make('Nakha'),
                                Checkbox::make('Akshisneha'),
                                Checkbox::make('Loma'),
                                Checkbox::make('Shmashru'),
                            ])->columns(4),
                    ]),

                // 3. Srotasa & Rogamarga (Combined for layout efficiency)
                Section::make('Pathology & Channels')
                    ->schema([
                        Fieldset::make('3) Srotasa & Srotodushti Type')
                            ->schema([
                                Checkbox::make('Sanaga')->label('Sanaga'),
                                Checkbox::make('Vimargagamana')->label('Vimargagamana'),
                                Checkbox::make('Atipravrutti')->label('Atipravrutti'),
                                Checkbox::make('Sira_granthi')->label('Sira Granthi'),
                            ])->columns(4),

                        Fieldset::make('7) Rogamarga')
                            ->schema([
                                Checkbox::make('Koshtha'),
                                Checkbox::make('Shakha'),
                                Checkbox::make('Marma'),
                            ])->columns(3),
                    ]),

                // 4. Examination Factors (Agni, Locations, Nature)
                Section::make('Examination Factors')
                    ->columns(2)
                    ->schema([
                        Radio::make('Agni')
                            ->label('4) Agni')
                            ->options([
                                'Sama' => 'Sama',
                                'Vishama' => 'Vishama',
                                'Tikshna' => 'Tikshna',
                                'Manda' => 'Manda',
                            ])->inline(),

                        Radio::make('Udbhavasthana')
                            ->label('5) Udbhavasthana')
                            ->options([
                                'Ama' => 'Ama',
                                'Pakwa' => 'Pakwa',
                            ])->inline(),

                        Radio::make('Adhishthana')
                            ->label('6) Adhishthana')
                            ->options([
                                'Ama' => 'Ama',
                                'Pakwa' => 'Pakwa',
                            ])->inline(),
                    ]),

                // 8. Vyadhi Swarupa (Nature of Disease)
                Section::make('8) Vyadhi Swarupa')
                    ->columns(3)
                    ->schema([
                        Radio::make('Vyadhi_swarupa1')
                            ->label('Onset')
                            ->options([
                                'chirakaari' => 'Chirakaari',
                                'aasukaari' => 'Aasukaari',
                            ]),
                        Radio::make('Vyadhi_swarupa2')
                            ->label('Severity')
                            ->options([
                                'mrudu' => 'Mrudu',
                                'daaruna' => 'Daaruna',
                            ]),
                        Radio::make('Vyadhi_swarupa3')
                            ->label('Chronicity')
                            ->options([
                                'naveena' => 'Naveena',
                                'jeerna' => 'Jeerna',
                            ]),
                    ]),

                // Detailed Clinical Notes
                Section::make('Clinical Observations')
                    ->schema([
                        Grid::make(2)->schema([
                            Textarea::make('Nidaana')
                                ->label('Nidaana (Etiology)')
                                ->rows(3),
                            Textarea::make('Poorvarupa')
                                ->label('Poorvarupa (Prodromal Symptoms)')
                                ->rows(3),
                            Textarea::make('Roopa')
                                ->label('Roopa (Signs & Symptoms)')
                                ->rows(3),
                            Textarea::make('Sampraapti')
                                ->label('Sampraapti (Pathogenesis)')
                                ->rows(3),
                        ]),

                        Grid::make(2)->schema([
                            Textarea::make('Upashaya')
                                ->label('Upashaya')
                                ->rows(2),
                            Textarea::make('Anupashaya')
                                ->label('Anupashaya')
                                ->rows(2),
                        ]),
                    ]),

                // Diagnosis & Prognosis
                Section::make('Diagnosis & Prognosis')
                    ->schema([
                        Textarea::make('Sambhavitha_vyadhi')
                            ->label('Sambhavitha Vyadhi')
                            ->rows(2),

                        Textarea::make('Rogavinischaya')
                            ->label('Rogavinischaya (Final Diagnosis)')
                            ->rows(2),

                        Grid::make(2)
                            ->schema([
                                Radio::make('Vyadhi_avastha1')
                                    ->label('Vyadhi Avastha (State)')
                                    ->options([
                                        'saama' => 'SAAMA',
                                        'niraama' => 'NIRAAMA',
                                    ])->inline(),

                                Radio::make('Vyadhi_avastha2')
                                    ->label('Vyadhi Avastha (Depth)')
                                    ->options([
                                        'Utthana' => 'UTTHANA',
                                        'Gambhira' => 'GAMBHIRA',
                                    ])->inline(),
                            ]),

                        Radio::make('Prognosis')
                            ->label('Saadhyaasaadhyataa (Prognosis)')
                            ->options([
                                'saadhya' => 'Saadhya',
                                'krichchhrasaadhya' => 'Krichchhrasaadhya',
                                'yaapya' => 'Yaapya',
                                'pratyaakheya' => 'Pratyaakheya',
                                'asadhya' => 'Asadhya',
                            ])
                            ->inline()
                            ->columnSpanFull(),
                    ]),

                // Additional Notes
                Section::make('Complications & Etiology')
                    ->schema([
                        Textarea::make('Upadrava')
                            ->label('Upadrava (Complications)')
                            ->rows(3),
                        Textarea::make('Nidana')
                            ->label('Nidana (Detailed Etiology)')
                            ->rows(3),
                    ]),
            ])->columns()
        ]);

    }

}
