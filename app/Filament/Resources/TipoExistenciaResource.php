<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoExistenciaResource\Pages;
use App\Filament\Resources\TipoExistenciaResource\RelationManagers;
use App\Models\TipoExistencia;
use Filament\Forms;
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

class TipoExistenciaResource extends Resource
{
    protected static ?string $model = TipoExistencia::class;

    protected static ?string $navigationLabel = 'Tipos de existencia';
    protected static ?string $label = 'tipos de existencia';
    protected static ?string $pluralLabel = 'Tipos de existencia';

    protected static ?string $navigationGroup = 'Existencias';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-chevron-up-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('descripcion'),
                Toggle::make('estado')
                    ->label('Activo')
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nombre')
                    ->searchable(),

                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTipoExistencias::route('/'),
        ];
    }
}
