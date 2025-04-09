<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoCajaResource\Pages;
use App\Filament\Resources\MovimientoCajaResource\RelationManagers;
use App\Models\MovimientoCaja;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoCajaResource extends Resource
{
    protected static ?string $model = MovimientoCaja::class;

    protected static ?string $navigationGroup = 'Caja';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema(fn(string $operation) => match ($operation) {
                        'view' => [
                            Placeholder::make('usuario')
                                ->content(function ($record) {
                                    // Busca el usuario por el user_id del registro
                                    $user = \App\Models\User::find($record->user_id);
                                    return $user ? $user->name : 'Usuario no encontrado';
                                })
                                ->label('Usuario'),
                        ],
                        default => [
                            Hidden::make('user_id')
                                ->default(auth()->id())
                                ->dehydrated(true)
                                ->afterStateHydrated(function (Hidden $component) {
                                    $component->state(auth()->id());
                                }),
                            Placeholder::make('usuario')
                                ->content(auth()->user()->name)
                                ->label('Usuario'),
                        ],
                    })
                    ->columnSpan('full'),
                Forms\Components\Select::make('caja_id')
                    ->relationship('caja', 'nombre', function ($query) {
                        $query->where('estado', 'Abierta');
                    })
                    ->required(),
                Forms\Components\Select::make('tipo_transaccion')
                    ->options([
                        'Ingreso' => 'Ingreso',
                        'Egreso' => 'Egreso',
                    ]),
                Forms\Components\Select::make('medio_pago_id')
                    ->relationship('medioPago', 'nombre', function ($query) {
                        $query->where('estado', true);
                    })
                    ->required(),
                Forms\Components\TextInput::make('monto')
                    ->prefix('S/.')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('caja.nombre'),
                Tables\Columns\TextColumn::make('tipo_transaccion'),
                Tables\Columns\TextColumn::make('medioPago.nombre')
                    ->badge(),
                Tables\Columns\TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
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

            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMovimientoCajas::route('/'),
        ];
    }
}
