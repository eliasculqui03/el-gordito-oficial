<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Filament\Resources\EmpleadoResource\RelationManagers;
use App\Models\Empleado;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Información  del empleado')
                                    ->schema([
                                        Forms\Components\TextInput::make('nombre')
                                            ->label('Nombre completo')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('tipo_documento_id')
                                            ->relationship('tipoDocumento', 'descripcion_corta')
                                            ->required(),
                                        Forms\Components\TextInput::make('numero_documento')
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('telefono')
                                            ->label('Numero de teléfono')
                                            ->tel()
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Correo elétronico')
                                            ->email()
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('direccion')
                                            ->label('Domicilio')
                                            ->maxLength(255)
                                            ->default(null),
                                    ])->columns(2)
                                    ->columnSpan(3),

                                Section::make('Información financiera')
                                    ->schema([
                                        Forms\Components\TextInput::make('entidad_bancaria')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('numero_cuenta')
                                            ->label('Número de cuenta')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('sueldo')
                                            ->numeric()
                                            ->prefix('S/.')
                                            ->default(null),
                                    ])->columnSpan(1)
                            ])->columns(4),


                        Grid::make()
                            ->schema([
                                Section::make('Horario')
                                    ->schema([
                                        Forms\Components\TimePicker::make('hora_entrada')
                                            ->default(null),
                                        Forms\Components\TimePicker::make('hora_salida')
                                            ->default(null),

                                    ])->columns(2)
                                    ->columnSpan(2),

                            ])->columns(3),
                        Grid::make()
                            ->schema([
                                Forms\Components\Toggle::make('estado')
                                    ->required()
                                    ->default(true),
                            ])->columnSpan(1),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre completo ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoDocumento.descripcion_corta'),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->label('Número de documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Domicilio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entidad_bancaria')
                    ->searchable(),

                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
