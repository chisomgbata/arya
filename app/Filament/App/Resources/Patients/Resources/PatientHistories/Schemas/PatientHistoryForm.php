<?php

namespace App\Filament\App\Resources\Patients\Resources\PatientHistories\Schemas;

use App\Models\Anupana;
use App\Models\Disease;
use App\Models\DiseaseType;
use App\Models\DiseaseTypeMedicine;
use App\Models\Medicine;
use App\Models\MedicineForm;
use App\Models\Panchakarma;
use App\Models\TimeOfAdministration;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;

class PatientHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')->schema([
                    Tabs\Tab::make('Info')
                        ->schema(function () {
                            $globalAnupanas = Anupana::query()->pluck('Name', 'Id');
                            $globalTimeOfAdministrations = TimeOfAdministration::query()->pluck('Name', 'Id');
                            $globalMedicineForms = MedicineForm::query()->pluck('Name', 'Id');
                            return [

                                Select::make("diseases")
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->relationship(
                                        name: "diseases",
                                        titleAttribute: "Name",
                                        modifyQueryUsing: fn($query) => $query->select(['Diseases.Id', 'Diseases.Name'])
                                    )
                                    ->preload()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $data = self::getDiseaseTypesData($state);
                                        $set('diseases_types_display', $data);
                                        $set('symptoms', []);
                                    })
                                ,

                                Placeholder::make('disease_information_display')
                                    ->hiddenLabel()
                                    ->label('')
                                    ->dehydrated(false)
                                    ->content(function (Get $get) {
                                        $ids = $get('diseases') ?? [];

                                        $diseases = Disease::whereIn('Id', $ids)->get();

                                        return view('diseases', [
                                            'diseases' => $diseases,
                                        ]);
                                    })
                                    ->columnSpanFull(),

                                Select::make("symptoms")
                                    ->multiple()
                                    ->relationship("symptoms", "Name", modifyQueryUsing: fn($query, Get $get) => $query
                                        ->whereHas('diseaseTypes', fn($q) => $q->whereIn('diseaseId', $get('diseases'))
                                        )
                                        ->distinct()
                                        ->select(['Symptoms.Id', 'Symptoms.Name'])
                                    )
                                    ->columnSpan(2)
                                    ->preload()
                                ,
                                Select::make('modern_symptoms')
                                    ->relationship('modernSymptoms', 'Name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('Name')->required(),
                                        Textarea::make('Description'),
                                    ])->createOptionUsing(function () {

                                    }),
                                Repeater::make('diseases_types_display')
                                    ->hiddenLabel()
                                    ->dehydrated(false)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->cloneable(false)
                                    ->reorderable(false)
                                    ->columns(8)
                                    ->columnSpanFull()
                                    ->default([])
                                    ->table([
                                        Repeater\TableColumn::make('Disease'),
                                        Repeater\TableColumn::make('Medicine'),
                                        Repeater\TableColumn::make('Disease Type'),
                                        Repeater\TableColumn::make('Symptoms'),
                                    ])
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        $diseases = $get('diseases');
                                        if (!empty($diseases)) {
                                            $set('diseases_types_display', self::getDiseaseTypesData($diseases));
                                        }
                                    })
                                    ->schema(fn(Get $get, Set $set) => [
                                        Hidden::make('type_name'),
                                        Hidden::make('description'),
                                        Placeholder::make('disease')
                                            ->label('Disease'),
                                        Actions::make([
                                            Action::make('manage_medicines')
                                                ->label('Meds')
                                                ->icon('hugeicons-medicine-02')
                                                ->color('primary')
                                                ->modal()
                                                ->schema(fn(Get $get) => [
                                                    Repeater::make('Medicines')
                                                        ->deletable(false)
                                                        ->addable(false)
                                                        ->cloneable(false)
                                                        ->reorderable(false)
                                                        ->hintActions([
                                                            Action::make('add_medicine')
                                                                ->modalHeading(fn() => 'Add Medicine For ' . $get('disease') . ' : ' . $get('type_name'))
                                                                ->schema([
                                                                    Hidden::make('DiseaseTypeId')->default(fn() => $get('type_id')),
                                                                    Hidden::make('IsSpecial')->default(true),
                                                                    Select::make('MedicineId')->label('Medicine')->searchable()->options(function ($query) {
                                                                        return Medicine::query()->pluck('Name', 'Id');
                                                                    }),
                                                                    TextInput::make('Dose')->required(),

                                                                    Select::make('TimeOfAdministrationId')
                                                                        ->label('Time of Administration')
                                                                        ->searchable()
                                                                        ->options($globalTimeOfAdministrations),

                                                                    Select::make('AnupanaId')
                                                                        ->label('Anupana')
                                                                        ->searchable()
                                                                        ->options($globalAnupanas),

                                                                    TextInput::make('Duration')->required(),
                                                                ])->action(function ($data) {
                                                                    DiseaseTypeMedicine::create($data);
                                                                })->button()
                                                        ])
                                                        ->table([
                                                            Repeater\TableColumn::make('Select'),
                                                            Repeater\TableColumn::make('MedicineName'),
                                                            Repeater\TableColumn::make('MedicineFormName'),
                                                            Repeater\TableColumn::make('Dose'),
                                                            Repeater\TableColumn::make('TimeOfAdministration'),
                                                            Repeater\TableColumn::make('Anupana'),
                                                            Repeater\TableColumn::make('Duration'),
                                                        ])
                                                        ->schema(function () use ($globalAnupanas, $globalTimeOfAdministrations, $globalMedicineForms) {
                                                            return [
                                                                Checkbox::make('Selected'),
                                                                Hidden::make('MedicineId'),
                                                                Hidden::make('MedicineName'),
                                                                Placeholder::make('MedicineName')
                                                                    ->label('Name')
                                                                ,

                                                                TextInput::make('MedicineFormName')
                                                                    ->label('Medicine Form')
                                                                    ->datalist($globalMedicineForms),

                                                                TextInput::make('Dose'),

                                                                TextInput::make('TimeOfAdministration')
                                                                    ->datalist($globalTimeOfAdministrations),

                                                                TextInput::make('Anupana')
                                                                    ->datalist($globalAnupanas),

                                                                TextInput::make('Duration'),
                                                            ];
                                                        })
                                                        ->default(function () use ($get) {
                                                            return DiseaseTypeMedicine::query()
                                                                ->where('DiseaseTypeId', $get('type_id'))
                                                                ->where(fn($query) => $query->where('IsSpecial', false)->orWhere('CreatedBy', auth()->user()->Id))
                                                                ->with([
                                                                    'medicine' => fn($q) => $q->select(['Id', 'Name', 'MedicineFormId']),
                                                                    'medicine.medicineForm' => fn($q) => $q->select(['Id', 'Name']),
                                                                    'timeOfAdministration' => fn($q) => $q->select(['Id', 'Name']),
                                                                    'anupana' => fn($q) => $q->select(['Id', 'Name']),

                                                                ])
                                                                ->select(['Id', 'MedicineId', 'Dose', 'Duration', 'TimeOfAdministrationId', 'AnupanaId'])
                                                                ->get()
                                                                ->map(fn($item) => [
                                                                    'MedicineId' => $item->medicine?->Id,
                                                                    'MedicineName' => $item->medicine?->Name,
                                                                    'MedicineFormName' => $item->medicine?->medicineForm?->Name,
                                                                    'Dose' => $item->Dose,
                                                                    'TimeOfAdministration' => $item->timeOfAdministration?->Name,
                                                                    'Anupana' => $item->anupana?->Name,
                                                                    'Duration' => $item->Duration,
                                                                ])
                                                                ->toArray();
                                                        })
                                                ])->modalWidth(Width::SevenExtraLarge)
                                                ->action(function ($data) use ($get, $set) {
                                                    $fields = collect($data['Medicines'])->where('Selected', true)->toArray();
                                                    $value = array_map(fn($field) => [
                                                        Str::uuid()->toString() => [
                                                            'MedicineId' => $field['MedicineId'] ?? '',
                                                            'MedicineFormName' => $field['MedicineFormName'] ?? '',
                                                            'Anupana' => $field['Anupana'] ?? '',
                                                            'Dose' => $field['Dose'] ?? '',
                                                            'TimeOfAdministration' => $field['TimeOfAdministration'] ?? '',
                                                            'Duration' => $field['Duration'] ?? '',
                                                            'Amount' => 0,
                                                        ]
                                                    ], $fields);

                                                    $set('Prescriptions', array_filter(array_merge($get('Prescriptions'),
                                                            ...$value))
                                                    );
                                                })
                                        ])->columnSpan(1),
                                        Actions::make([
                                            Action::make('disease_type_detail')
                                                ->label(fn(Get $get) => $get('type_name')) // Dynamically fetch the text
                                                ->link()
                                                ->modal()
                                                ->modalSubmitAction(false)
                                                ->modalCancelAction(false)
                                                ->schema([
                                                    Placeholder::make('description')
                                                        ->hiddenLabel()
                                                ]),
                                        ])->columnSpan(1),

                                        ViewField::make('symptoms_ui')->hiddenLabel() // Name doesn't matter, it doesn't save data
                                        ->view('checkboxes')
                                            ->columnSpan(6)
                                        ,

                                        Hidden::make('type_id'),

                                    ]),


                                Repeater::make('Prescriptions')
                                    ->relationship('prescriptions')
                                    ->table([

                                        Repeater\TableColumn::make('MedicineName'),
                                        Repeater\TableColumn::make('MedicineFormName'),
                                        Repeater\TableColumn::make('Dose'),
                                        Repeater\TableColumn::make('TimeOfAdministration'),
                                        Repeater\TableColumn::make('Anupana'),
                                        Repeater\TableColumn::make('Duration'),
                                        Repeater\TableColumn::make('Amount'),
                                    ])
                                    ->schema([
                                        Hidden::make('MedicineId'),
                                        Placeholder::make('MedicineName')->content(fn(Get $get) => Medicine::where('Id', $get('MedicineId'))?->select('Name')->first()->Name),

                                        TextInput::make('MedicineFormName')
                                            ->datalist($globalMedicineForms),

                                        TextInput::make('Dose'),

                                        TextInput::make('TimeOfAdministration')
                                            ->datalist($globalTimeOfAdministrations),

                                        TextInput::make('Anupana')
                                            ->datalist($globalAnupanas),
                                        TextInput::make('Duration'),

                                        TextInput::make('Amount')
                                            ->extraAttributes(['class' => 'medicine-amount'])
                                            ->afterStateUpdatedJs(<<<'JS'
        let prescriptions = $get('../')
        let total = Object.values(prescriptions).reduce((acc, curr) => {
        return acc + (+curr.Amount || 0);
        }, 0);
        $set('../../MedicinesFee', total)
        JS
                                            )
                                            ->numeric(),
                                    ])
                                    ->compact()
                                    ->columnSpanFull()
                                    ->cloneable(),

                                TextInput::make('ConsultationFee')
                                    ->numeric(),
                                TextInput::make('MedicinesFee')
                                    ->id('medicine-fee')
                                    ->numeric(),

                                DateTimePicker::make('NextAppointmentDate'),
                                Textarea::make('Remark')
                                    ->columnSpanFull(),
                                Textarea::make('Note')
                                    ->columnSpanFull(),
                            ];
                        })->columns(3),
                    Tabs\Tab::make("Gynec History")->schema([
                        Fieldset::make("Gynec History")
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
                                        $masters = Panchakarma::all();

                                        $existing = collect($state ?? []);

                                        $items = [];

                                        foreach ($masters as $master) {
                                            $found = $existing->firstWhere('PanchakarmaId', $master->Id);

                                            if ($found) {
                                                $items[] = $found;
                                            } else {
                                                $items[] = [
                                                    'PanchakarmaId' => $master->Id,
                                                    'Detail' => null,
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

    protected static function getDiseaseTypesData($diseaseIds): array
    {
        if (empty($diseaseIds)) {
            return [];
        }

        $diseaseTypes = DiseaseType::query()->with(['symptoms' => function ($q) {
            $q->select('Symptoms.Id', 'Symptoms.Name', 'Symptoms.NameEnglish', 'Symptoms.NameGujarati');
        }])->whereIn('diseaseId', $diseaseIds)
            ->get();


        return $diseaseTypes->map(function ($type) {
            return [
                'type_id' => $type->Id,
                'type_name' => $type->Name ?? 'Unknown Type',
                'description' => $type->Description ?? 'Kaboom',
                'disease' => $type->Disease->Name ?? "",
                'symptoms_options' => $type->symptoms->select('Name', 'NameEnglish', 'NameGujarati', 'Id')->toArray(),
            ];
        })->toArray();
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
