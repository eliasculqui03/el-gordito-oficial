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
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('capacidad')
                    ->placeholder('Numero de personas')
                    ->required()
                    ->numeric()
                    ->minValue(4),
                Forms\Components\Select::make('zona_id')
                    ->label('Zonas')
                    ->required()
                    ->relationship('zona', 'nombre'),
                Forms\Components\Select::make('estado')
                    ->options([
                        'Libre' => 'Libre',
                        'Ocupada' => 'Ocupada',
                        'Inhablitada' => 'Inhabilitada'
                    ])
                    ->default('Libre'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->searchable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('capacidad')
                    ->numeric()
                    ->description('personas')
                    ->toggleable()

                    ->alignment('center'),
                Tables\Columns\TextColumn::make('zona.nombre')
                    ->label('Zona'),
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
