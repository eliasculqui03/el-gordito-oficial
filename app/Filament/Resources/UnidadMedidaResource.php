<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnidadMedidaResource\Pages;
use App\Filament\Resources\UnidadMedidaResource\RelationManagers;
use App\Models\UnidadMedida;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnidadMedidaResource extends Resource
{
    protected static ?string $model = UnidadMedida::class;


    protected static ?string $navigationLabel = 'Unidades de medida';
    protected static ?string $label = 'unidades de medida';
    protected static ?string $pluralLabel = 'Unidades de medida';

    protected static ?string $navigationGroup = 'Existencias';
    //protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-italic';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('simbolo')
                    ->label('Símbolo')
                    ->required(),
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
                TextColumn::make('simbolo')
                    ->label('Símbolo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUnidadMedidas::route('/'),
        ];
    }
}
