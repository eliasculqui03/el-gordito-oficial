<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExistenciaResource\Pages;
use App\Filament\Resources\ExistenciaResource\RelationManagers;
use App\Models\Existencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tipo_existencia_id')
                    ->relationship('tipoExistencia', 'nombre')
                    ->required(),
                Forms\Components\Select::make('categoria_existencia_id')
                    ->relationship('categoriaExistencia', 'nombre')
                    ->required(),
                Forms\Components\Select::make('unidad_medida_id')
                    ->relationship('unidadMedida', 'nombre')
                    ->required(),
                Forms\Components\TextInput::make('costo_compra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('precio_venta')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('descripcion')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('estado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoExistencia.nombre'),
                Tables\Columns\TextColumn::make('categoriaExistencia.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidadMedida.nombre'),
                Tables\Columns\TextColumn::make('costo_compra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('precio_venta')
                    ->numeric()
                    ->sortable(),
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
                //
                Tables\Filters\SelectFilter::make('tipo_existencia_id')
                    ->label('Tipo existencia')
                    ->relationship('tipoExistencia', 'nombre'),
                Tables\Filters\SelectFilter::make('categoria_existencia_id')
                    ->label('Categoria existencia')
                    ->relationship('categoriaExistencia', 'nombre'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
