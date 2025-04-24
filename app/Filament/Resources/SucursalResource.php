<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SucursalResource\Pages;
use App\Filament\Resources\SucursalResource\RelationManagers;
use App\Models\Sucursal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SucursalResource extends Resource
{
    protected static ?string $model = Sucursal::class;

    protected static ?string $navigationLabel = 'Sucursales';
    protected static ?string $label = 'Sucursal';
    protected static ?string $pluralLabel = 'Sucursales';

    protected static ?string $navigationGroup = 'Empresa';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('empresa_id')
                    ->relationship('empresa', 'nombre')
                    ->required(),
                Forms\Components\TextInput::make('tipo_establecimiento')
                    ->label('Tipo de establecimiento')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_inicio_operaciones')
                    ->label('Inicio de operaciones')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_final_operaciones')
                    ->label('Fin de operaciones')
                    ->required(),
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('correo')
                    ->label('Correo electrónico')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empresa.nombre'),
                Tables\Columns\TextColumn::make('tipo_establecimiento')
                    ->searchable(),

                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('F. de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null)
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSucursals::route('/'),
            'create' => Pages\CreateSucursal::route('/create'),
            'edit' => Pages\EditSucursal::route('/{record}/edit'),
        ];
    }
}
