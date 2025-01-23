<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenCompraResource\Pages;
use App\Filament\Resources\OrdenCompraResource\RelationManagers;
use App\Models\OrdenCompra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdenCompraResource extends Resource
{
    protected static ?string $navigationGroup = 'Compras';
    //protected static ?int $navigationSort = 1;

    protected static ?string $model = OrdenCompra::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->default(auth()->user()->id)
                    ->label('Usuario'),
                Forms\Components\Select::make('metodo_pago')
                    ->options([
                        'Efectivo' => 'Efectivo',
                        'Yape' => 'Yape',
                    ])
                    ->required(),
                Forms\Components\Select::make('factura')
                    ->options([
                        'Boleta' => 'Boleta',
                        'Factura' => 'Factura',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('compras')
                    ->imageEditor()
                    ->default(null),
                Forms\Components\TextInput::make('igv')
                    ->required()
                    ->numeric()
                    ->readonly()
                    ->default(18)
                    ->prefix('%'),
                Forms\Components\TextInput::make('total')
                    ->prefix('S/.')
                    ->default(0)
                    ->required()
                    ->readonly()
                    ->numeric()
                    ->live(),
                Forms\Components\Repeater::make('detalleOrdenCompra')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('solicitud_compra_id')
                            ->label('Solicitud de compra')
                            ->placeholder('Sin solicitud de compra')
                            ->relationship(
                                'solicitudCompra',
                                'id',
                                fn($query) => $query->where('estado', 'Aprobada')->with('existencia')
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "ID {$record->id} - {$record->existencia->nombre}")
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, Forms\Get $get) {
                                if ($state) {
                                    // Si selecciona una solicitud
                                    $pedidoCompra = \App\Models\SolicitudCompra::find($state);
                                    if ($pedidoCompra) {
                                        $set('existencia_id', $pedidoCompra->existencia_id);
                                        $set('cantidad', $pedidoCompra->cantidad);
                                        $set('subtotal', $pedidoCompra->total);
                                    }
                                } else {
                                    // Si selecciona "Sin solicitud de compra"
                                    $set('existencia_id', null);
                                    $set('cantidad', 1);
                                    $set('subtotal', 0);
                                }
                                $detalles = $get('../../detalleOrdenCompra');
                                if (is_array($detalles)) {
                                    $total = array_reduce($detalles, function ($carry, $item) {
                                        return $carry + (float)($item['subtotal'] ?? 0);
                                    }, 0);
                                    $set('../../total', round($total, 2));
                                }
                            })
                            ->columnSpan(1),

                        Forms\Components\Select::make('existencia_id')
                            ->relationship('existencia', 'nombre', function ($query) {
                                return $query->where('estado', true);
                            })
                            ->required()
                            ->live()
                            //
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state && !$get('solicitud_compra_id')) {
                                    $existencia = \App\Models\Existencia::find($state);
                                    if ($existencia) {
                                        $cantidad = $get('cantidad') ?? 1;
                                        $subtotal = $existencia->costo_compra * $cantidad;
                                        $set('subtotal', round($subtotal, 2));

                                        // Calcular el total
                                        $detalles = $get('../../detalleOrdenCompra');
                                        if (is_array($detalles)) {
                                            $total = array_reduce($detalles, function ($carry, $item) {
                                                return $carry + (float)($item['subtotal'] ?? 0);
                                            }, 0);
                                            $set('../../total', round($total, 2));
                                        }
                                    }
                                }
                            })
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('cantidad')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($get('existencia_id')) {
                                    $existencia = \App\Models\Existencia::find($get('existencia_id'));
                                    if ($existencia && is_numeric($state)) {
                                        $subtotal = $existencia->costo_compra * $state;
                                        $set('subtotal', round($subtotal, 2));

                                        // Calcular el total
                                        $detalles = $get('../../detalleOrdenCompra');
                                        if (is_array($detalles)) {
                                            $total = array_reduce($detalles, function ($carry, $item) {
                                                return $carry + (float)($item['subtotal'] ?? 0);
                                            }, 0);
                                            $set('../../total', round($total, 2));
                                        }
                                    }
                                }
                            })
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('subtotal')
                            ->required()
                            ->readonly()
                            ->numeric()
                            ->prefix('S/.')
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                // Calcular el total cuando cambia cualquier subtotal
                                $detalles = $get('../../detalleOrdenCompra');
                                if (is_array($detalles)) {
                                    $total = array_reduce($detalles, function ($carry, $item) {
                                        return $carry + (float)($item['subtotal'] ?? 0);
                                    }, 0);
                                    $set('../../total', round($total, 2));
                                }
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(1)
                    ->columnSpan('full')
                    ->grid(3)

                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (is_array($state)) {
                            $total = array_reduce($state, function ($carry, $item) {
                                return $carry + (float)($item['subtotal'] ?? 0);
                            }, 0);
                            $set('total', round($total, 2));
                        }
                    })



            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('factura')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('igv')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('detalleOrdenCompra.existencia.nombre')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListOrdenCompras::route('/'),
            'create' => Pages\CreateOrdenCompra::route('/create'),
            'edit' => Pages\EditOrdenCompra::route('/{record}/edit'),
        ];
    }
}
