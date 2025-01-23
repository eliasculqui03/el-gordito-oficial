<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoComprobanteResource\Pages;
use App\Filament\Resources\TipoComprobanteResource\RelationManagers;
use App\Models\TipoComprobante;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoComprobanteResource extends Resource
{
    protected static ?string $model = TipoComprobante::class;


    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('codigo')
                    ->label('Código sunat')
                    ->required(),
                TextInput::make('descripcion')
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

                TextColumn::make('codigo')
                    ->sortable()
                    ->searchable()
                    ->label('Codigo sunat'),

                TextColumn::make('descripcion')
                    ->label('Descripción'),
                Tables\Columns\IconColumn::make('estado')
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
            'index' => Pages\ManageTipoComprobantes::route('/'),
        ];
    }
}
