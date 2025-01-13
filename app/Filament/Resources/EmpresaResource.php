<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\Empresa;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationGroup = 'Empresa';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema(
                    [
                        Section::make('Informacion')

                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('tipo_actividad')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('ruc')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nombre_comercial')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('numero_decreto')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('logo')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(4),

                    ]
                )->columnSpan(4),
                Group::make()->schema(
                    [
                        Section::make('Informacion')

                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('telefono')
                                    ->tel()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('direccion')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('moneda')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\Textarea::make('mision')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('vision')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('descripcion')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('facebook')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('youtube')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('whatsapp')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('nombre_gerente')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('dni_gerente')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('telefono_gerente')
                                    ->tel()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('correo_gerente')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('direccion_gerente')
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('fecha_ingreso_gerente')
                                    ->maxLength(255)
                                    ->default(null),
                            ])->columns(4),

                    ]
                )->columnSpan(4),


            ])->columns(8);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_actividad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_comercial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_decreto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('moneda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('facebook')
                    ->searchable(),
                Tables\Columns\TextColumn::make('youtube')
                    ->searchable(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dni_gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono_gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo_gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion_gerente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ingreso_gerente')
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
