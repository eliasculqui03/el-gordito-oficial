<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CajaResource\Pages;
use App\Filament\Resources\CajaResource\RelationManagers;
use App\Models\Caja;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CajaResource extends Resource
{
    protected static ?string $model = Caja::class;

    protected static ?string $navigationGroup = 'Caja';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete(false),
                Forms\Components\Select::make('sucursal_id')
                    ->relationship('sucursal', 'nombre')
                    ->required(),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('saldo_actual')
                            ->prefix('S/.')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\Select::make('estado')
                            ->required()
                            ->options(
                                [
                                    'Abierta' => 'Abierta',
                                    'Cerrada' => 'Cerrada',
                                    'Deshabilitada' => 'Deshabilitada',
                                ]
                            )
                            ->default('Cerrada'),

                    ])->columns(3),
                CheckboxList::make('zonas')
                    ->relationship('zonas',  'nombre',  function ($query) {
                        $query->where('estado', true); // Filtra las mesas cuyo estado es true
                    })
                    ->searchable()
                    ->columns(3),
                Forms\Components\Select::make('users')
                    ->label('Cajeros')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->preload()
                    ->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre'),
                Tables\Columns\TextColumn::make('saldo_actual')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'success' => 'Abierta',
                        'danger' => 'Cerrada',
                        'gray' => 'Deshabilitada',
                    ]),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCajas::route('/'),
            'create' => Pages\CreateCaja::route('/create'),
            'edit' => Pages\EditCaja::route('/{record}/edit'),
        ];
    }
}
