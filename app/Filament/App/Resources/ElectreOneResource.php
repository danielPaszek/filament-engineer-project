<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ElectreOneResource\Pages;
use App\Filament\App\Resources\ElectreOneResource\RelationManagers;
use App\Infolists\Components\TestEntry;
use App\Models\Dataset;
use App\Models\ElectreOne;
use App\Models\Variant;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ElectreOneResource extends Resource
{
    protected static ?string $model = ElectreOne::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\Select::make('project_id')
//                    ->relationship('project', 'name')
//                    ->required(),
                Forms\Components\TextInput::make('lambda')
                    ->required()
                    ->numeric(),
//                Forms\Components\TextInput::make('concordance')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('discordance')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('combined')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('relations')
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('clean_graph')
//                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lambda')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('concordance')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discordance')
                    ->searchable(),
                Tables\Columns\TextColumn::make('combined')
                    ->searchable(),
                Tables\Columns\TextColumn::make('relations')
                    ->searchable(),
                Tables\Columns\TextColumn::make('clean_graph')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        /** @var ElectreOne $record */
        $record = $infolist->getRecord();
//        try {
            self::myInitData($record);

//        } catch (\Exception $exception) {
//            throw new \Exception("Probably connection with spring");
//        }
//        $electreCriteria = $record->electreCriteriaSettings;
//        $electreCriteriaCount = $electreCriteria->count();
        $variants = Filament::getTenant()->variants;
        $variantCount = $variants->count();
        $concordanceColumns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $concordanceColumns[] = TestEntry::make('concordance.' . $i)->label((string)$i);
        }
        $disconcordanceCoulumns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $disconcordanceCoulumns[] = TestEntry::make('discordance.' . $i)->label((string)$i);
        }
        $combinedColumns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $combinedColumns[] = TestEntry::make('final.' . $i)->label((string)$i);
        }
        $relationsColumns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $relationsColumns[] = TestEntry::make('relations.' . $i)->label((string)$i);
        }
        return $infolist->schema([
            TextEntry::make('lambda'),
            Section::make('tables')
                ->schema([
                    Section::make('concordance')
                        ->schema(
                            $concordanceColumns
                        )
                        ->columns(3),
                    Section::make('discordance')
                        ->schema(
                            $disconcordanceCoulumns
                        )
                        ->columns(3),
                    Section::make('final')
                        ->schema(
                            $combinedColumns
                        )
                        ->columns(3),
                    Section::make('relations')
                        ->schema(
                            $relationsColumns
                        )
                        ->columns(3),
                    TextEntry::make('clean_graph'),
                ]),

        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ElectreCriteriaSettingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListElectreOnes::route('/'),
            'create' => Pages\CreateElectreOne::route('/create'),
            'view' => Pages\ViewElectreOne::route('/{record}'),
            'edit' => Pages\EditElectreOne::route('/{record}/edit'),
        ];
    }

    protected static function myInitData(?\Illuminate\Database\Eloquent\Model $record)
    {
//        Create Service
//        url with containers could have some problems???
        $response = Http::asJson()->post('host.docker.internal:8080/electre1s', ['data' => [
            'lambda' => 0.5,
            'criteria' => [
                [
                    "preferenceType" => "cost",
                    "weight" => 1.0,
                    "q" => 0.9,
                    "p" => 2.2,
                    "v" => 3.0
                ],
                [
                    "preferenceType" => "gain",
                    "weight" => 9.0,
                    "q" => 1.0,
                    "p" => 1.6,
                    "v" => 3.5
                ]
            ],
            "variants" => [
                [
                    "values" => [
                        10.8,
                        4.7
                    ]
                ],
                [
                    "values" => [
                        8.0,
                        6.0
                    ]
                ],
                [
                    "values" => [
                        11.0,
                        4.8
                    ]
                ]
            ],
            "b" => [
                [
                    "values" => [
                        10.8,
                        4.7
                    ]
                ],
                [
                    "values" => [
                        8.0,
                        6.0
                    ]
                ],
                [
                    "values" => [
                        11.0,
                        4.8
                    ]
                ]
            ]
        ]
        ]);
        $body = json_decode($response->body());
        foreach ($body as $key => $matrix) {
            $record[$key] = Arr::flatten($matrix);
        }
        return $record;
    }
}
