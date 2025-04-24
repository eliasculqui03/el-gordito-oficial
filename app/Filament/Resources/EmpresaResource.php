<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationGroup = 'Empresa';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([


                        Grid::make()
                            ->schema([
                                Section::make('Información general')

                                    ->schema([
                                        Forms\Components\TextInput::make('nombre')
                                            ->label('Razón social')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('tipo_actividad')
                                            ->label('Tipo de actividad')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('ruc')
                                            ->label('RUC')
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->maxLength(11),
                                        Forms\Components\TextInput::make('nombre_comercial')
                                            ->label('Nombre del comercial')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('numero_decreto')
                                            ->label('N.° de decreto')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\FileUpload::make('logo')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('empresa')
                                            ->imageEditorAspectRatios([
                                                null,
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ]),
                                    ])->columns(2)
                                    ->columnSpan(2),

                                Section::make('Información detallada')

                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->label('Correo ')
                                            ->placeholder('example@email.com')
                                            ->email()
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('telefono')
                                            ->label('N.° de teléfono')
                                            ->tel()
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('direccion')
                                            ->label('Dirección')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('moneda')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\Textarea::make('mision')
                                            ->label('Misión')
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('vision')
                                            ->label('Visión')
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('descripcion')
                                            ->label('Descripción')
                                            ->columnSpanFull(),
                                    ])->columns(2)
                                    ->columnSpan(1),

                            ])->columns(3),

                        Grid::make()
                            ->schema([

                                Section::make('Redes Sociales')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook')
                                            ->placeholder('www.facebook.com')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('youtube')
                                            ->placeholder('www.youtube.com')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('whatsapp')
                                            ->placeholder('www.whastapp.com')
                                            ->maxLength(255)
                                            ->default(null),
                                    ])->columnSpan(1),

                                Section::make('Información del gerente')
                                    ->schema([
                                        Forms\Components\TextInput::make('nombre_gerente')
                                            ->label('Nombre del gerente')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('dni_gerente')
                                            ->label('N.° de documento')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('telefono_gerente')
                                            ->label('Teléfono')
                                            ->tel()
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('correo_gerente')
                                            ->label('Correo electrónico')
                                            ->placeholder('example@email.com')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\TextInput::make('direccion_gerente')
                                            ->label('Domicilio')
                                            ->maxLength(255)
                                            ->default(null),
                                        Forms\Components\DatePicker::make('fecha_ingreso_gerente')
                                            ->label('Fecha de ingreso al cargo')
                                            ->default(null),
                                    ])->columnSpan(1)
                                    ->columns(2),
                            ])
                    ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre de la empresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_actividad')
                    ->label('Tipo de actividad')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_comercial')
                    ->label('Nombre del comercial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_decreto')
                    ->label('Número de decreto')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('F. de creación')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('F. de actualización')
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
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
