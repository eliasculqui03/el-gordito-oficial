<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SesionCajaResource\Pages;
use App\Filament\Resources\SesionCajaResource\RelationManagers;
use App\Models\SesionCaja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SesionCajaResource extends Resource
{
    protected static ?string $model = SesionCaja::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('caja_id')
                    ->relationship('caja', 'id')
                    ->required(),
                Forms\Components\TextInput::make('fecha_apertura')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fecha_cierra')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('saldo_inicial')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('saldo_cierre')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('estado')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('caja.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_apertura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_cierra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('saldo_inicial')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo_cierre')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSesionCajas::route('/'),
            'create' => Pages\CreateSesionCaja::route('/create'),
            'edit' => Pages\EditSesionCaja::route('/{record}/edit'),
        ];
    }
}
