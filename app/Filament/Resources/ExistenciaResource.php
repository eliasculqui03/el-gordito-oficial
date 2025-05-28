<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExistenciaResource\Pages;
use App\Models\Caja;
use App\Models\CategoriaExistencia;
use App\Models\Existencia;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ExistenciaResource extends Resource
{
    protected static ?string $model = Existencia::class;

    protected static ?string $navigationGroup = 'Existencias';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255)
                            ->autocomplete(false),
                        Forms\Components\Select::make('tipo_existencia_id')
                            ->label('Tipo de existencia')
                            ->relationship('tipoExistencia',  'nombre',  function ($query) {
                                $query->where('estado', true);
                            })
                            ->live() // Esto hace que el campo se actualice en tiempo real
                            ->afterStateUpdated(fn(callable $set) => $set('categoria_existencia_id', null)) // Reset categoria cuando cambia el tipo
                            ->required(),

                        Forms\Components\Select::make('categoria_existencia_id')
                            ->label('Categoria')
                            ->options(function (callable $get) {
                                $tipoExistenciaId = $get('tipo_existencia_id');

                                if (!$tipoExistenciaId) {
                                    return [];
                                }

                                return CategoriaExistencia::where('tipo_existencia_id', $tipoExistenciaId)
                                    ->pluck('nombre', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn(callable $get) => !$get('tipo_existencia_id')), // Deshabilita si no hay tipo seleccionado
                        Forms\Components\Select::make('unidad_medida_id')
                            ->label('Unidad de medida')
                            ->relationship('unidadMedida',  'descripcion',  function ($query) {
                                $query->where('estado', true);
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('area_existencia_id')
                            ->label('Área')
                            ->relationship('areaExistencia',  'nombre',  function ($query) {
                                $query->where('estado', true);
                            })
                            ->required(),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción'),
                        Forms\Components\Toggle::make('estado')
                            ->default(true),
                    ])->columnSpan(2)
                    ->columns(2),

                Section::make('Precios')
                    ->schema([
                        Forms\Components\TextInput::make('precio_compra')
                            ->label('Precio de compra')
                            ->prefix('S/.')
                            ->minValue(0)
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('precio_venta')
                            ->label('Precio de venta')
                            ->prefix('S/.')
                            ->minValue(0)
                            ->required()
                            ->numeric(),
                    ])->columnSpan(1),

                Section::make('Precios por Caja')
                    ->schema([
                        Repeater::make('cajas')
                            ->label('')
                            ->schema([
                                Select::make('caja_id')
                                    ->label('Caja')
                                    ->options(function () {
                                        return Caja::where('estado', '!=', 'Deshabilitada')
                                            ->pluck('nombre', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(1),

                                TextInput::make('precio_venta')
                                    ->label('Precio de venta en Caja')
                                    ->numeric()
                                    ->step(0.01)
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->defaultItems(4)
                            ->addActionLabel('Agregar Precio')
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (!isset($state['caja_id'])) {
                                    return 'Nueva Caja';
                                }

                                $caja = Caja::find($state['caja_id']);
                                return $caja ? $caja->nombre : 'Caja no encontrada';
                            })
                            // ->reorderableWithButtons()
                            ->columns(1)
                            ->columnSpan('full')
                            ->grid(4)
                    ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoExistencia.nombre')
                    ->searchable()
                    ->label('Tipo'),
                Tables\Columns\TextColumn::make('categoriaExistencia.nombre')
                    ->label('Categoria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidadMedida.simbolo')
                    ->label('U. de medida'),
                Tables\Columns\TextColumn::make('precio_compra')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    })
                    ->label('P. de compra')
                    ->sortable(),
                Tables\Columns\TextColumn::make('precio_venta')
                    ->numeric()

                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    })
                    ->label('P. de venta')
                    ->sortable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Fecha de creación')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Fecha de actualización')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('tipo_existencia_id')
                    ->label('Tipo')
                    ->relationship('tipoExistencia', 'nombre'),
                Tables\Filters\SelectFilter::make('categoria_existencia_id')
                    ->label('Categoria')
                    ->searchable()
                    ->preload()
                    ->relationship('categoriaExistencia', 'nombre'),
                Tables\Filters\SelectFilter::make('area_existencia_id')
                    ->label('Área')
                    ->relationship('areaExistencia', 'nombre'),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        '1' => 'Habilitado',
                        '2' => 'Deshabilitado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExistencias::route('/'),
            'create' => Pages\CreateExistencia::route('/create'),
            'edit' => Pages\EditExistencia::route('/{record}/edit'),
        ];
    }
}
