<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaExistenciaResource\Pages;
use App\Filament\Resources\AreaExistenciaResource\RelationManagers;
use App\Models\AreaExistencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaExistenciaResource extends Resource
{
    protected static ?string $model = AreaExistencia::class;

    protected static ?string $navigationGroup = 'Existencias';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete(false),
                Forms\Components\Select::make('users')
                    ->label('Agregar usuarios')
                    ->relationship('users', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\Textarea::make('descripcion')
                    ->columnSpanFull()
                    ->autocomplete(false),
                Forms\Components\Toggle::make('estado')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Usuarios')
                    ->badge(),
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
            ])
            ->actions([

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAreaExistencias::route('/'),
        ];
    }
}
