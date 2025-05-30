<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDocumentoResource\Pages;
use App\Filament\Resources\TipoDocumentoResource\RelationManagers;
use App\Models\TipoDocumento;
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

class TipoDocumentoResource extends Resource
{
    protected static ?string $model = TipoDocumento::class;

    protected static ?string $navigationLabel = 'Tipos de documento';
    protected static ?string $label = 'tipo de documento';
    protected static ?string $pluralLabel = 'Tipos de documentos';

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tipo')
                    ->required()
                    ->autocomplete(false),
                TextInput::make('descripcion_larga')
                    ->required()
                    ->autocomplete(false),
                TextInput::make('descripcion_corta')
                    ->required()
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
                TextColumn::make('tipo')
                    ->searchable(),
                TextColumn::make('descripcion_larga')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('descripcion_corta')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
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
            'index' => Pages\ManageTipoDocumentos::route('/'),
        ];
    }
}
