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
        self::myInitData($record);
//        $electreCriteria = $record->electreCriteriaSettings;
//        $electreCriteriaCount = $electreCriteria->count();
        $variants = Filament::getTenant()->hasManyThrough(Variant::class, Dataset::class);
        $variantCount = $variants->count();
        $concordanceColumns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $concordanceColumns[] = TestEntry::make('concordance.' . $i)->label((string)$i);
        }
        $disconcordanceCoulmns = [];
        for ($i = 0; $i < $variantCount * $variantCount; $i++) {
            $disconcordanceCoulmns[] = TestEntry::make('disconcordance.' . $i)->label((string)$i);
        }
        return $infolist->schema([
            TextEntry::make('lambda'),
            Section::make('concordance')
                ->schema(
                            $concordanceColumns
                        )
            ->columns(3),
            Section::make('disconcordance')
                ->schema(
                    $disconcordanceCoulmns
                )
                ->columns(3),
            TextEntry::make('combined'),
            TextEntry::make('relations'),
            TextEntry::make('clean_graph'),
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
        $record['concordance'] = Arr::flatten( [
                [
                    1.0,
                    0.45000000000000034,
                    1.0
                ],
                [
                    1.0,
                    1.0,
                    1.0
                ],
                [
                    1.0,
                    0.5999999999999998,
                    1.0
                ]
            ]);
        $record['disconcordance'] = Arr::flatten( [
            [
                0.0,
                0.0,
                0.0
            ],
            [
                0.0,
                0.0,
                0.0
            ],
            [
                0.0,
                1.0,
                0.0
            ]
        ]);
    }
}
