<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZonaResource\Pages;
use App\Filament\Resources\ZonaResource\RelationManagers;
use App\Models\Zona;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ZonaResource extends Resource
{
    protected static ?string $model = Zona::class;

    protected static ?string $navigationGroup = 'Mesas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->autocomplete(false)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->default(null),
                CheckboxList::make('cajas')
                    ->relationship('cajas', 'nombre', function ($query) {
                        $query->whereIn('estado', ['Abierta', 'Cerrada']);
                    })
                    ->searchable()
                    ->columns(3),
                Forms\Components\Select::make('users')
                    ->label('Mozos')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\Toggle::make('estado')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Mozos')
                    ->badge(),
                Tables\Columns\TextColumn::make('cajas.nombre')
                    ->label('Cajas')
                    ->badge()
                    ->color('info'),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
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

                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageZonas::route('/'),
        ];
    }
}
