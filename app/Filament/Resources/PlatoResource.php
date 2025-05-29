<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatoResource\Pages;
use App\Filament\Resources\PlatoResource\RelationManagers;
use App\Models\Caja;
use App\Models\Plato;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PlatoResource extends Resource
{
    protected static ?string $model = Plato::class;


    protected static ?string $navigationLabel = 'Platos y bebidas';
    protected static ?string $label = 'plato o bebida';
    protected static ?string $pluralLabel = 'Platos y Bebidas';

    protected static ?string $navigationGroup = 'Platos y bebidas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([

                        Section::make('Información General')
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->autocomplete(false),
                                Forms\Components\Select::make('categoria_plato_id')
                                    ->required()
                                    ->relationship('categoriaPlato', 'nombre'),

                                Forms\Components\Select::make('unidad_medida_id')
                                    ->label('Unidad de medida')
                                    ->required()
                                    ->relationship('unidadMedida', 'descripcion')
                                    ->searchable()
                                    ->preload()
                                    ->default(58),
                                Forms\Components\Select::make('area_id')
                                    ->relationship('area', 'nombre', function ($query) {
                                        $query->where('estado', true); // Filtra las cajas activas
                                    })
                                    ->required(),
                                Forms\Components\Textarea::make('descripcion')
                                    ->columnSpanFull()
                                    ->autocomplete(false),
                                Forms\Components\Toggle::make('estado')
                                    ->default(true)
                                    ->required(),
                            ])->columns(2)
                            ->columnSPan(3),

                        Section::make('Precio Estandar')
                            ->schema([
                                Forms\Components\TextInput::make('precio')
                                    ->required()
                                    ->minValue(0)
                                    ->numeric()
                                    ->prefix('S/.'),
                                Forms\Components\TextInput::make('precio_llevar')
                                    ->required()
                                    ->minValue(0)
                                    ->numeric()
                                    ->prefix('S/.'),
                            ])->columnSpan(1),
                    ])->columns(4),


                Section::make('Precio por caja')
                    ->schema([
                        Forms\Components\Repeater::make('precios_cajas')
                            ->label('')
                            ->schema([
                                Forms\Components\Select::make('caja_id')
                                    ->label('Caja')
                                    ->options(function () {
                                        return Caja::where('estado', '!=', 'Deshabilitada')
                                            ->pluck('nombre', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                Forms\Components\TextInput::make('precio')
                                    ->label('Precio en Caja')
                                    ->numeric()
                                    ->required()
                                    ->prefix('S/.'),

                                Forms\Components\TextInput::make('precio_llevar')
                                    ->label('Precio para Llevar')
                                    ->numeric()
                                    ->required()
                                    ->prefix('S/.'),
                            ])
                            ->defaultItems(4)
                            ->addActionLabel('Agregar Caja')
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (!isset($state['caja_id'])) {
                                    return 'Nueva Caja';
                                }

                                $caja = Caja::find($state['caja_id']);
                                return $caja ? $caja->nombre : 'Caja no encontrada';
                            })
                            ->columns(1)
                            ->grid(4),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoriaPlato.nombre')
                    ->label('Categoría')
                    ->searchable(),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('precio_llevar')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidadMedida.descripcion')
                    ->label('U. de medida'),
                Tables\Columns\TextColumn::make('area.nombre')
                    ->label('Área'),
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
                Tables\Filters\SelectFilter::make('categoria_plato_id')
                    ->label('Categoria')
                    ->relationship('categoriaPlato', 'nombre'),

                Tables\Filters\SelectFilter::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'nombre'),
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
            'index' => Pages\ListPlatos::route('/'),
            'create' => Pages\CreatePlato::route('/create'),
            'edit' => Pages\EditPlato::route('/{record}/edit'),
        ];
    }
}
