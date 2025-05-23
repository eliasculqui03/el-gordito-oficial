<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoriaExistenciaResource\Pages;
use App\Filament\Resources\CategoriaExistenciaResource\RelationManagers;
use App\Models\CategoriaExistencia;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriaExistenciaResource extends Resource
{
    protected static ?string $model = CategoriaExistencia::class;

    protected static ?string $navigationLabel = 'Categoria de existencias';
    protected static ?string $label = 'categoria';
    protected static ?string $pluralLabel = 'Categoria de existencias';

    protected static ?string $navigationGroup = 'Existencias';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_existencia_id')
                    ->required()
                    ->relationship('tipoExistencia', 'nombre'),

                TextInput::make('nombre')
                    ->required()
                    ->autocomplete(false),
                TextInput::make('descripcion')
                    ->label('Descripción')
                    ->columnSpanFull()
                    ->autocomplete(false),
                Toggle::make('estado')
                    ->label('Activo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('tipoExistencia.nombre')
                    ->searchable(),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategoriaExistencias::route('/'),
        ];
    }
}
