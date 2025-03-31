<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalidaAlmacenResource\Pages;
use App\Filament\Resources\SalidaAlmacenResource\RelationManagers;
use App\Models\SalidaAlmacen;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SalidaAlmacenResource extends Resource
{
    protected static ?string $model = SalidaAlmacen::class;

    protected static ?string $navigationLabel = 'Salidas Almacen';
    protected static ?string $label = 'salida';
    protected static ?string $pluralLabel = 'Salidas almacen';

    protected static ?string $navigationGroup = 'Inventario';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
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
                                    }),
                                Forms\Components\Select::make('comanda_existencia_id')
                                    ->label('Listas de existencias pedidas por comandas')
                                    ->relationship(
                                        'comandaExistencia',
                                        'id',
                                        fn($query) => $query->where('estado', 'Pendiente')->with(['existencia', 'existencia.unidadMedida'])
                                    )
                                    ->getOptionLabelFromRecordUsing(fn($record) => "# {$record->id} - {$record->existencia->nombre}")
                                    ->placeholder('Sin comanda')
                                    // Para modo view
                                    ->disabled(fn($operation) => $operation === 'view')
                                    ->live()
                                    // Agregamos hydratación cuando carga el registro
                                    ->afterStateHydrated(function ($state, callable $set, $record, $operation) {
                                        if ($state && ($operation === 'edit' || $operation === 'view')) {
                                            $comandaExistencia = \App\Models\ComandaExistencia::with(['existencia', 'existencia.unidadMedida'])
                                                ->find($state);

                                            if ($comandaExistencia) {
                                                $set('existencia_id', $comandaExistencia->existencia_id);
                                                $set('cantidad', $comandaExistencia->cantidad);

                                                // Para modo view, también cargar el almacén (que no cargamos en creación)
                                                if ($operation === 'view' && $record) {
                                                    $set('almacen_id', $record->almacen_id);
                                                } else {
                                                    // En modo edición o creación, buscar el almacén con más stock
                                                    $inventario = \App\Models\Inventario::where('existencia_id', $comandaExistencia->existencia_id)
                                                        ->orderBy('stock', 'desc')
                                                        ->first();

                                                    if ($inventario) {
                                                        $set('almacen_id', $inventario->almacen_id);
                                                    }
                                                }
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, $component) {
                                        if ($state) {
                                            $comandaExistencia = \App\Models\ComandaExistencia::with(['existencia', 'existencia.unidadMedida'])
                                                ->find($state);

                                            if ($comandaExistencia) {
                                                // Verificar si hay suficiente stock en algún almacén
                                                $inventarioDisponible = \App\Models\Inventario::where('existencia_id', $comandaExistencia->existencia_id)
                                                    ->where('stock', '>=', $comandaExistencia->cantidad)
                                                    ->orderBy('stock', 'desc')
                                                    ->first();

                                                if (!$inventarioDisponible) {
                                                    // Buscar el stock total disponible
                                                    $stockTotal = \App\Models\Inventario::where('existencia_id', $comandaExistencia->existencia_id)
                                                        ->sum('stock');

                                                    Notification::make()
                                                        ->title('Stock insuficiente')
                                                        ->warning()
                                                        ->body("Stock requerido: {$comandaExistencia->cantidad}, Stock disponible total: {$stockTotal}")
                                                        ->persistent()
                                                        ->send();

                                                    // Limpiar selección
                                                    $component->state(null);
                                                    $set('existencia_id', null);
                                                    $set('cantidad', null);
                                                    $set('almacen_id', null);
                                                    return;
                                                }

                                                // Si hay stock suficiente, establecer los valores
                                                $set('existencia_id', $comandaExistencia->existencia_id);
                                                $set('cantidad', $comandaExistencia->cantidad);
                                                $set('almacen_id', $inventarioDisponible->almacen_id);

                                                // Mostrar notificación de éxito
                                                Notification::make()
                                                    ->title('Stock disponible')
                                                    ->success()
                                                    ->body("Stock disponible en almacén seleccionado: {$inventarioDisponible->stock} unidades")
                                                    ->send();
                                            }
                                        } else {
                                            // Limpiar todos los campos cuando se deselecciona
                                            $set('existencia_id', null);
                                            $set('cantidad', null);
                                            $set('almacen_id', null);
                                        }
                                    })
                                    ->default(null),
                            ])->columns(2),
                    ]),
                Grid::make()
                    ->schema([
                        Section::make('Existencia')
                            ->schema([
                                Forms\Components\Select::make('existencia_id')
                                    ->relationship(
                                        'existencia',
                                        'nombre',
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn(Forms\Get $get): bool => filled($get('comanda_existencia_id')))
                                    ->dehydrated()
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - {$record->unidadMedida->descripcion}")
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, Forms\Components\Select $component) {
                                        if ($state) {
                                            // Verificar si existe en algún almacén
                                            $existeEnInventario = \App\Models\Inventario::where('existencia_id', $state)
                                                ->where(
                                                    'stock',
                                                    '>',
                                                    0
                                                )
                                                ->exists();

                                            if (!$existeEnInventario) {
                                                // Si no existe en ningún almacén, mostrar error y limpiar la selección
                                                Notification::make()
                                                    ->title('Existencia sin inventario')
                                                    ->warning()
                                                    ->body('Esta existencia no tiene stock disponible en ningún almacén.')
                                                    ->send();

                                                $component->state(null);
                                                $set('almacen_id', null);
                                                return;
                                            }

                                            // Si existe, buscar el almacén con más stock
                                            $inventario = \App\Models\Inventario::where('existencia_id', $state)
                                                ->where(
                                                    'stock',
                                                    '>',
                                                    0
                                                )
                                                ->orderBy('stock', 'desc')
                                                ->first();

                                            if ($inventario) {
                                                $set('almacen_id', $inventario->almacen_id);

                                                // Opcional: Mostrar notificación de éxito
                                                Notification::make()
                                                    ->title('Stock disponible')
                                                    ->success()
                                                    ->body("Stock disponible en almacén: {$inventario->stock} unidades")
                                                    ->send();
                                            }
                                        } else {
                                            $set('almacen_id', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('cantidad')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->disabled(fn(Forms\Get $get): bool => filled($get('comanda_existencia_id')))
                                    ->dehydrated()
                                    ->live()
                                    ->rules([
                                        function (Get $get) {
                                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                                $existenciaId = $get('existencia_id');
                                                $almacenId = $get('almacen_id');

                                                if (!$existenciaId || !$almacenId) {
                                                    return;
                                                }

                                                $stockDisponible = \App\Models\Inventario::where('existencia_id', $existenciaId)
                                                    ->where('almacen_id', $almacenId)
                                                    ->value('stock') ?? 0;

                                                if ($value > $stockDisponible) {
                                                    $fail("La cantidad solicitada ({$value}) excede el stock disponible en el almacén ({$stockDisponible})");
                                                }
                                            };
                                        }
                                    ])
                                    ->afterStateUpdated(function ($state, Get $get, callable $set) {
                                        // Opcional: Mostrar stock disponible al cambiar la cantidad
                                        $existenciaId = $get('existencia_id');
                                        $almacenId = $get('almacen_id');

                                        if ($existenciaId && $almacenId) {
                                            $stockDisponible = \App\Models\Inventario::where('existencia_id', $existenciaId)
                                                ->where('almacen_id', $almacenId)
                                                ->value('stock') ?? 0;

                                            if ($state > $stockDisponible) {
                                                Notification::make()
                                                    ->warning()
                                                    ->title('Stock insuficiente')
                                                    ->body("Stock disponible: {$stockDisponible}")
                                                    ->send();
                                            }
                                        }
                                    }),
                            ])->columnSpan(1),
                        Section::make('Almacén')
                            ->schema([
                                Forms\Components\Select::make('almacen_id')
                                    ->label('Almacén')
                                    ->relationship('almacen', 'nombre')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\TextInput::make('nota')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columnSpan(1)

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('comandaExistencia.id')
                    ->label('ID Comanda E.')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('existencia.nombre'),
                Tables\Columns\TextColumn::make('almacen.nombre')
                    ->label('Almacén'),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric(),
                Tables\Columns\TextColumn::make('existencia.unidadMedida.nombre')
                    ->label('U. de medida'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de salida')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de actuaización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
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
            'index' => Pages\ListSalidaAlmacens::route('/'),
            'create' => Pages\CreateSalidaAlmacen::route('/create'),
            'edit' => Pages\EditSalidaAlmacen::route('/{record}/edit'),
        ];
    }
}
