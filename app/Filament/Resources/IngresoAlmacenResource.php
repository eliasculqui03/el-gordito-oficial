<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresoAlmacenResource\Pages;
use App\Filament\Resources\IngresoAlmacenResource\RelationManagers;
use App\Models\IngresoAlmacen;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IngresoAlmacenResource extends Resource
{
    protected static ?string $model = IngresoAlmacen::class;

    protected static ?string $navigationLabel = 'Ingresos Almacen';
    protected static ?string $label = 'ingreso';
    protected static ?string $pluralLabel = 'Ingresos almacen';


    protected static ?string $navigationGroup = 'Inventario';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detalles')
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
                            })->columnSpan(2),

                        Forms\Components\Select::make('detalle_orden_compra_id')
                            ->label('Detalle de Orden de Compra')
                            ->placeholder('Seleccione un detalle de orden de compra')
                            ->relationship(
                                'detalleOrdenCompra',
                                'id',
                                fn($query) => $query->whereHas('ordenCompra', function ($query) {
                                    $query->where('estado', 'Pagada');
                                })->with(['existencia'])
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "ID: {$record->id} - {$record->existencia->nombre} - Cantidad: {$record->cantidad}")
                            ->preload()
                            ->default(null)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $detalleOrdenCompra = \App\Models\DetalleOrdenCompra::find($state);
                                    if ($detalleOrdenCompra) {
                                        $set('existencia_id', $detalleOrdenCompra->existencia_id);
                                        $set('cantidad', $detalleOrdenCompra->cantidad);
                                        $set('existencia_id_disabled', true);
                                        $set('cantidad_disabled', true);
                                    }
                                } else {
                                    // Limpiar los campos cuando no hay selección
                                    $set('existencia_id', null);
                                    $set('cantidad', null);
                                    $set('existencia_id_disabled', false);
                                    $set('cantidad_disabled', false);
                                }
                            })->columnSpan(2),
                    ])->columns(4),

                Section::make('Agregar existencia')
                    ->schema([
                        Forms\Components\Select::make('existencia_id')
                            ->relationship('existencia', 'nombre', fn($query) => $query->where('estado', 1))
                            ->required()

                            ->disabled(fn($get) => $get('existencia_id_disabled'))
                            ->dehydrated(true)
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - {$record->unidadMedida->nombre}")
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('cantidad')
                            ->required()
                            ->numeric()
                            ->disabled(fn($get) => $get('cantidad_disabled'))
                            ->dehydrated(),
                        Forms\Components\Select::make('almacen_id')
                            ->relationship('almacen', 'nombre')
                            ->required(),
                    ])->columns(4),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('detalleOrdenCompra.id')
                    ->numeric()
                    ->label('ID de compra')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('existencia.nombre'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0, '.', ',');
                    }),
                Tables\Columns\TextColumn::make('existencia.unidadMedida.simbolo')
                    ->label('U. de medida'),
                Tables\Columns\TextColumn::make('almacen.nombre'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de ingreso')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Fecha de actualización')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListIngresoAlmacens::route('/'),
            'create' => Pages\CreateIngresoAlmacen::route('/create'),
            'edit' => Pages\EditIngresoAlmacen::route('/{record}/edit'),
        ];
    }
}
