<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioResource\Pages;
use App\Filament\Resources\InventarioResource\RelationManagers;
use App\Models\Inventario;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static ?string $navigationGroup = 'Inventario';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('existencia_id')
                    ->relationship('existencia', 'nombre')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'La existencia ya existe en inventario.'
                    ])
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - {$record->unidadMedida->nombre}")
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('stock')
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
                Tables\Columns\TextColumn::make('existencia.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0, '.', ',');
                    }),
                Tables\Columns\TextColumn::make('existencia.unidadMedida.simbolo')
                    ->label('U. de medida'),
                Tables\Columns\TextColumn::make('almacen.nombre'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualización')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('almacen_id')
                    ->label('Almacen')
                    ->relationship('almacen', 'nombre'),
            ])
            ->actions([

                Action::make('aumentar_stock')
                    ->label('Agregar stock')
                    ->icon('heroicon-o-plus')
                    ->color('info')
                    ->action(function (Inventario $record, array $data): void {
                        $record->increment('stock', $data['cantidad']);
                        $record->save();
                    })
                    ->form([
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('cantidad')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->required(),
                            ])->columns(1)
                    ])->modalWidth('sm'),
                Action::make('disminuir_stock')
                    ->label('Eliminar stock')
                    ->icon('heroicon-o-minus')
                    ->color('danger')
                    ->action(function (Inventario $record, array $data): void {
                        $cantidad = $data['cantidad'];
                        if ($cantidad > $record->stock) {
                            $cantidad = $record->stock;
                        }
                        $record->decrement('stock', $cantidad);
                        $record->save();
                    })
                    ->form([
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('cantidad')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->required(),
                            ])->columns(1)
                    ])->modalWidth('sm'),

                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInventarios::route('/'),
        ];
    }
}
