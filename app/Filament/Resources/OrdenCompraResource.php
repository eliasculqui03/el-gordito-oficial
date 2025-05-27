<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenCompraResource\Pages;
use App\Filament\Resources\OrdenCompraResource\RelationManagers;
use App\Models\OrdenCompra;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdenCompraResource extends Resource
{
    protected static ?string $navigationGroup = 'Compras';
    //protected static ?int $navigationSort = 1;

    protected static ?string $model = OrdenCompra::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    // Función para calcular el total teniendo en cuenta el IGV
    private static function calcularTotal(array $detalles, bool $incluyeIgv): float
    {
        $subtotal = array_reduce($detalles, function ($carry, $item) {
            return $carry + (float)($item['subtotal'] ?? 0);
        }, 0);

        return $incluyeIgv ? round($subtotal * 1.18, 2) : round($subtotal, 2);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Información de compra')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema(fn(string $operation) => match ($operation) {
                                'view' => [
                                    Placeholder::make('usuario')
                                        ->content(function ($record) {
                                            // Busca el usuario por el user_id del registro
                                            $user = User::find($record->user_id);
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
                        Forms\Components\Select::make('metodo_pago')
                            ->label('Metodo de pago')
                            ->options([
                                'Efectivo' => 'Efectivo',
                                'Yape' => 'Yape',
                            ])
                            ->required(),
                        Forms\Components\Select::make('tipo_comprobante_id')
                            ->relationship('tipoComprobante', 'descripcion')
                            ->required(),
                        Forms\Components\Toggle::make('igv')
                            ->label('Incluir IGV 18% (sino esta incuido)')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (bool $state, Forms\Set $set, Forms\Get $get) {
                                // Recalcular el total cuando cambia el estado del IGV
                                $detalles = $get('detalleOrdenCompra');
                                if (is_array($detalles)) {
                                    $total = self::calcularTotal($detalles, $state);
                                    $set('total', $total);
                                }
                            }),
                    ])->columnSpan(1)
                    ->columns(2),

                Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->image()
                            ->directory('compras')
                            ->imageEditor()
                            ->default(null),
                    ])->columnSpan(1),
                Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('detalleOrdenCompra')
                            ->label('Detalle de compra')
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
                                    ->getOptionLabelFromRecordUsing(fn($record) => "ID {$record->id} - {$record->existencia->nombre} - {$record->existencia->unidadMedida->simbolo}")
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

                                        // Recalcular el total considerando el IGV
                                        $detalles = $get('../../detalleOrdenCompra');
                                        $incluyeIgv = $get('../../igv');
                                        if (is_array($detalles)) {
                                            $total = self::calcularTotal($detalles, $incluyeIgv);
                                            $set('../../total', $total);
                                        }
                                    })
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('existencia_id')
                                    ->relationship('existencia', 'nombre', function ($query) {
                                        return $query->where('estado', true);
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        if ($state && !$get('solicitud_compra_id')) {
                                            $existencia = \App\Models\Existencia::find($state);
                                            if ($existencia) {
                                                $cantidad = $get('cantidad') ?? 1;
                                                $subtotal = $existencia->precio_compra * $cantidad;
                                                $set('subtotal', round($subtotal, 2));

                                                // Recalcular el total considerando el IGV
                                                $detalles = $get('../../detalleOrdenCompra');
                                                $incluyeIgv = $get('../../igv');
                                                if (is_array($detalles)) {
                                                    $total = self::calcularTotal($detalles, $incluyeIgv);
                                                    $set('../../total', $total);
                                                }
                                            }
                                        }
                                    })
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - {$record->unidadMedida->simbolo}")
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(1)
                                    ->disabled(fn(Forms\Get $get): bool => filled($get('solicitud_compra_id')))
                                    ->dehydrated(),

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
                                                $subtotal = $existencia->precio_compra * $state;
                                                $set('subtotal', round($subtotal, 2));

                                                // Recalcular el total considerando el IGV
                                                $detalles = $get('../../detalleOrdenCompra');
                                                $incluyeIgv = $get('../../igv');
                                                if (is_array($detalles)) {
                                                    $total = self::calcularTotal($detalles, $incluyeIgv);
                                                    $set('../../total', $total);
                                                }
                                            }
                                        }
                                    })
                                    ->disabled(fn(Forms\Get $get): bool => filled($get('solicitud_compra_id')))
                                    ->dehydrated()
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('subtotal')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->prefix('S/.')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        // Recalcular el total considerando el IGV
                                        $detalles = $get('../../detalleOrdenCompra');
                                        $incluyeIgv = $get('../../igv');
                                        if (is_array($detalles)) {
                                            $total = self::calcularTotal($detalles, $incluyeIgv);
                                            $set('../../total', $total);
                                        }
                                    })
                                    ->columnSpan(1),
                            ])
                            ->columns(1)
                            ->columnSpan('full')
                            ->grid(3)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                // Recalcular el total considerando el IGV
                                $incluyeIgv = $get('igv');
                                if (is_array($state)) {
                                    $total = self::calcularTotal($state, $incluyeIgv);
                                    $set('total', $total);
                                }
                            }),
                    ]),

                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('total')
                            ->prefix('S/.')
                            ->default(0)
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->live(),
                    ])->columns(4)
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
                Tables\Columns\TextColumn::make('tipoComprobante.descripcion')
                    ->label('Comprobante')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable(),

                Tables\Columns\TextColumn::make('igv')
                    ->label('IGV')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Se inluyo' : 'Incluido')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('detalleOrdenCompra.existencia.nombre')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. de compra')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('F. de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null)
            ->filters([
                SelectFilter::make('igv')
                    ->label('IGV')
                    ->options([
                        '1' => 'Se inlcuyo',
                        '0' => 'Incluido',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_exacta')
                            ->label('Fecha exacta')
                            ->placeholder('Seleccione una fecha')
                            ->closeOnDateSelection(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_exacta'],
                                fn(Builder $query, $date): Builder => $query
                                    ->whereDate('created_at', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['fecha_exacta'] ?? null) {
                            $indicators['fecha_exacta'] = 'Órdenes del: ' . \Carbon\Carbon::parse($data['fecha_exacta'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
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
