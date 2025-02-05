<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MesaResource\Pages;
use App\Filament\Resources\MesaResource\RelationManagers;
use App\Models\Mesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MesaResource extends Resource
{
    protected static ?string $model = Mesa::class;

    protected static ?string $navigationGroup = 'Empresa';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('zona_id')
                    ->relationship('zona', 'nombre', function ($query) {
                        return $query->where('estado', true);
                    })
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->caja->nombre} - {$record->nombre}")
                    ->required(),
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('estado')
                    ->default('Libre')
                    ->options([
                        'Libre' => 'Libre',
                        'Ocupada' => 'Ocupada',
                        'Inhabilitada' => 'Inhabilitada'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('numero')
                    ->label('Numero de mesa')
                    ->numeric(),
                Tables\Columns\TextColumn::make('zona.nombre'),
                Tables\Columns\TextColumn::make('zona.caja.nombre')
                    ->label('Caja'),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'info' => 'Libre',
                        'danger' => 'Ocupada',
                        'gray' => 'Inhabilitada',
                    ]),
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
                Tables\Filters\SelectFilter::make('zona.caja_id')
                    ->label('Caja')
                    ->relationship('zona.caja', 'nombre'),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'Libre' => 'Libre',
                        'Ocupada' => 'Ocupada',
                        'Inhabilitada' => 'Inhabilitada'
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMesas::route('/'),
        ];
    }
}
