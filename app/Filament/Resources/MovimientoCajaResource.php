<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoCajaResource\Pages;
use App\Filament\Resources\MovimientoCajaResource\RelationManagers;
use App\Models\MovimientoCaja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoCajaResource extends Resource
{
    protected static ?string $model = MovimientoCaja::class;

    protected static ?string $navigationGroup = 'Caja';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

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
                Forms\Components\TextInput::make('tipo_transaccion')
                    ->required(),
                Forms\Components\Select::make('medio_pago_id')
                    ->relationship('medioPago', 'id')
                    ->required(),
                Forms\Components\TextInput::make('monto')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('descripcion')
                    ->maxLength(255)
                    ->default(null),
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
                Tables\Columns\TextColumn::make('tipo_transaccion'),
                Tables\Columns\TextColumn::make('medioPago.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMovimientoCajas::route('/'),
        ];
    }
}
