<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresoAlmacenResource\Pages;
use App\Filament\Resources\IngresoAlmacenResource\RelationManagers;
use App\Models\IngresoAlmacen;
use Filament\Forms;
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
    protected static ?string $label = 'Almacen';
    protected static ?string $pluralLabel = 'Almacenes';


    protected static ?string $navigationGroup = 'Inventario';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                            }
                        } else {
                            // Limpiar los campos cuando no hay selección
                            $set('existencia_id', null);
                            $set('cantidad', null);
                        }
                    }),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('existencia_id')
                    ->relationship('existencia', 'nombre')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('almacen_id')
                    ->relationship('almacen', 'nombre')
                    ->required(),

            ]);
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
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('existencia.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de creación')
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
