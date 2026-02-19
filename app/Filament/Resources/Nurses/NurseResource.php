<?php

namespace App\Filament\Resources\Nurses;

use App\Filament\Resources\Nurses\Pages;
use App\Models\Nurse;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class NurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ─── Personal Information ───
                Section::make('Personal Information')
                    ->description('Basic identification and contact details.')
                    ->schema([
                        FileUpload::make('user.profile_image_path')
                            ->label('Profile Photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('profile-images')
                            ->visibility('public')
                            ->imageEditor()
                            ->circleCropper()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->helperText('Accepted formats: JPG, PNG, WebP (Max 5MB)')
                            ->columnSpanFull(),

                        TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('contact_number')
                            ->label('Contact Number')
                            ->tel()
                            ->columnSpanFull(),

                        DatePicker::make('birthdate')
                            ->label('Date of Birth')
                            ->maxDate(now())
                            ->columnSpanFull(),

                        DatePicker::make('date_hired')
                            ->label('Date Hired')
                            ->default(now())
                            ->columnSpanFull(),

                        TextInput::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ]),

                // ─── Professional Details ───
                Section::make('Professional Details')
                    ->description('Licensure, role classification, and nurse type.')
                    ->schema([
                        TextInput::make('license_number')
                            ->label('PRC License Number')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('nurse_type_id')
                            ->relationship('nurseType', 'name')
                            ->label('Nurse Type / Specialization')
                            ->preload()
                            ->searchable()
                            ->nullable()
                            ->columnSpanFull(),

                        Select::make('designation')
                            ->label('Designation')
                            ->options([
                                'Clinical' => 'Clinical',
                                'Admitting' => 'Admitting',
                            ])
                            ->live()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('role_level')
                            ->label('Role Level')
                            ->options([
                                'Staff' => 'Staff',
                                'Charge' => 'Charge',
                                'Head' => 'Head',
                                'Supervisor' => 'Supervisor',
                                'Chief' => 'Chief',
                            ])
                            ->default('Staff')
                            ->live()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('station_id')
                            ->relationship('station', 'station_name')
                            ->label('Station Assignment')
                            ->preload()
                            ->searchable()
                            ->visible(fn(Get $get): bool => in_array($get('role_level'), ['Head', 'Staff']))
                            ->required(fn(Get $get): bool => in_array($get('role_level'), ['Head', 'Staff']))
                            ->columnSpanFull(),

                        Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->label('Unit / Building Assignment')
                            ->preload()
                            ->searchable()
                            ->visible(fn(Get $get): bool => $get('role_level') === 'Supervisor')
                            ->required(fn(Get $get): bool => $get('role_level') === 'Supervisor')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Employment Status')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->default('Active')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                // ─── Account Credentials ───
                Section::make('Account Credentials')
                    ->description('System login credentials for this nurse.')
                    ->hidden(fn(string $operation): bool => $operation === 'view')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation): string => $operation === 'create'
                                ? 'Initial Password'
                                : 'New Password (leave blank to keep current)')
                            ->formatStateUsing(fn() => null)
                            ->dehydrated(fn($state) => filled($state))
                            ->columnSpanFull(),
                    ]),

                // ─── Educational Background ───
                Section::make('Educational Background')
                    ->description('Academic qualifications and credentials.')
                    ->collapsible()
                    ->schema([
                        Repeater::make('educational_background')
                            ->label('')
                            ->schema([
                                TextInput::make('level')
                                    ->label('Degree Level')
                                    ->placeholder('e.g., Bachelor of Nursing, Master of Science')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('school')
                                    ->label('School / University')
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('year')
                                    ->label('Year Achieved')
                                    ->options(
                                        collect(range(now()->year, 1970))
                                            ->mapWithKeys(fn($y) => [$y => (string) $y])
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Add Education')
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ─── Personal Information ───
                Section::make('Personal Information')
                    ->schema([
                        ImageEntry::make('user.profile_image_path')
                            ->label('Profile Photo')
                            ->circular()
                            ->disk('public')
                            ->size(100)
                            ->defaultImageUrl(fn(Nurse $record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->first_name . ' ' . $record->last_name) . '&color=7F9CF5&background=EBF4FF'),

                        TextEntry::make('employee_id')
                            ->label('Badge ID'),

                        TextEntry::make('first_name')
                            ->label('First Name'),

                        TextEntry::make('last_name')
                            ->label('Last Name'),

                        TextEntry::make('contact_number')
                            ->label('Contact Number'),

                        TextEntry::make('birthdate')
                            ->label('Date of Birth')
                            ->date(),

                        TextEntry::make('date_hired')
                            ->label('Date Hired')
                            ->date(),

                        TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                // ─── Professional Details ───
                Section::make('Professional Details')
                    ->schema([
                        TextEntry::make('license_number')
                            ->label('PRC License Number'),

                        TextEntry::make('nurseType.name')
                            ->label('Nurse Type / Specialization'),

                        TextEntry::make('designation')
                            ->label('Designation')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Admitting' => 'warning',
                                'Clinical' => 'success',
                                default => 'gray',
                            }),

                        TextEntry::make('role_level')
                            ->label('Role Level')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Chief' => 'danger',
                                'Supervisor' => 'warning',
                                'Head' => 'primary',
                                'Charge' => 'info',
                                default => 'gray',
                            }),

                        TextEntry::make('station.station_name')
                            ->label('Station Assignment'),

                        TextEntry::make('station.unit.name')
                            ->label('Unit / Building')
                            ->visible(fn(?Nurse $record): bool => $record && in_array($record->role_level, ['Head', 'Staff'])),

                        TextEntry::make('unit.name')
                            ->label('Unit / Building')
                            ->visible(fn(?Nurse $record): bool => $record && $record->role_level === 'Supervisor'),

                        TextEntry::make('status')
                            ->label('Employment Status')
                            ->badge()
                            ->color(fn(string $state): string => $state === 'Active' ? 'success' : 'gray'),
                    ])
                    ->columns(2),

                // ─── Educational Background ───
                Section::make('Educational Background')
                    ->schema([
                        RepeatableEntry::make('educational_background')
                            ->label('')
                            ->schema([
                                TextEntry::make('level')
                                    ->label('Degree Level'),

                                TextEntry::make('school')
                                    ->label('School / University'),

                                TextEntry::make('year')
                                    ->label('Year Achieved'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('user.profile_image_path')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->checkFileExistence(false)
                    ->defaultImageUrl(fn(Nurse $record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->first_name . ' ' . $record->last_name) . '&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('employee_id')
                    ->label('Badge ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),

                TextColumn::make('designation')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Admitting' => 'warning',
                        'Clinical' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('role_level')
                    ->label('Role Level')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Chief' => 'danger',
                        'Supervisor' => 'warning',
                        'Head' => 'primary',
                        'Charge' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('nurseType.name')
                    ->label('Nurse Type')
                    ->default('-')
                    ->sortable(),

                TextColumn::make('station.station_name')
                    ->label('Station')
                    ->default('-')
                    ->sortable()
                    ->visible(fn(?Nurse $record): bool => $record && in_array($record->role_level, ['Head', 'Staff', 'Chief'])),

                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->default('-')
                    ->sortable()
                    ->visible(fn(?Nurse $record): bool => $record && in_array($record->role_level, ['Supervisor', 'Chief'])),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('designation')
                    ->options([
                        'Clinical' => 'Clinical',
                        'Admitting' => 'Admitting',
                    ]),

                SelectFilter::make('role_level')
                    ->label('Role Level')
                    ->options([
                        'Staff' => 'Staff',
                        'Charge' => 'Charge',
                        'Head' => 'Head',
                        'Supervisor' => 'Supervisor',
                        'Chief' => 'Chief',
                    ]),

                SelectFilter::make('nurse_type_id')
                    ->label('Nurse Type')
                    ->relationship('nurseType', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('station_id')
                    ->label('Station')
                    ->relationship('station', 'station_name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    \Filament\Actions\Action::make('viewStations')
                        ->label('View Stations')
                        ->icon('heroicon-o-building-library')
                        ->visible(fn(Nurse $record): bool => $record->role_level === 'Supervisor' && $record->unit_id)
                        ->slideOver()
                        ->modalContent(fn(Nurse $record): \Illuminate\View\View => view('filament.modals.supervisor-stations', [
                            'supervisor' => $record,
                            'stations' => $record->unit->stations ?? collect(),
                        ])),
                    \Filament\Actions\Action::make('dtrReport')
                        ->label('DTR Report')
                        ->icon('heroicon-o-document-text')
                        ->form([
                            Grid::make(2)->schema([
                                DatePicker::make('date_from')
                                    ->label('Date From')
                                    ->required(),
                                DatePicker::make('date_to')
                                    ->label('Date To')
                                    ->required(),
                            ]),
                        ])
                        ->modalSubmitActionLabel('Generate PDF')
                        ->action(function (Nurse $record, array $data) {
                            return redirect()->away(
                                route('admin.nurses.dtrReport', [
                                    'nurse' => $record->id,
                                    'date_from' => $data['date_from'],
                                    'date_to' => $data['date_to'],
                                ])
                            );
                        }),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                \Filament\Actions\Action::make('batchDtrReport')
                    ->label('Batch DTR Report')
                    ->icon('heroicon-o-document-chart-bar')
                    ->form([
                        Grid::make(2)->schema([
                            DatePicker::make('date_from')
                                ->label('Date From')
                                ->required(),
                            DatePicker::make('date_to')
                                ->label('Date To')
                                ->required(),
                        ]),
                    ])
                    ->modalSubmitActionLabel('Generate Batch PDF')
                    ->action(function (array $data) {
                        return redirect()->away(
                            route('admin.nurses.batchDtrReport', $data)
                        );
                    }),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNurses::route('/'),
            'create' => Pages\CreateNurse::route('/create'),
            'edit' => Pages\EditNurse::route('/{record}/edit'),
        ];
    }
}
