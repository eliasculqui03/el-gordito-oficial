<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $label = 'usuario';
    protected static ?string $pluralLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Información del usuario')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Correo electrónico')
                                            ->email()
                                            ->unique(ignoreRecord: true)
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('password')
                                            ->label(fn(string $operation) => $operation === 'edit' ? 'Nueva Contraseña' : 'Contraseña')
                                            ->password()
                                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                                            ->required(fn(string $operation): bool => $operation === 'create')
                                            ->revealable()
                                            ->dehydrated(fn($state) => filled($state))
                                    ])->columnSpan(1),

                                Section::make('Información de Empleado')
                                    ->schema([
                                        Select::make('empleado_id')
                                            ->relationship(
                                                'empleado',
                                                'nombre',
                                                function ($query) {
                                                    return $query->where('estado', true);
                                                }
                                            )
                                            ->searchable()
                                            ->preload(),
                                        FileUpload::make('foto')
                                            ->image()
                                            ->avatar()
                                            ->directory('usuarios')
                                            ->imageEditor()

                                            ->circleCropper(),
                                    ])->columnSpan(1),
                            ])->columns(2),
                        RichEditor::make('descripcion')
                            ->label('Descripción')
                            ->fileAttachmentsDirectory('usuarios_descrip')

                    ]),



            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Verificación'),
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
                Tables\Columns\TextColumn::make('empleado.nombre')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->label('Foto'), // Nombre de la columna en español
            ])
            ->filters([
                //filtros para la tabla
                Filter::make('verified')
                    ->label('Perfil verificado')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at'))
            ])
            ->actions([
                Action::make('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->action(function (User $user) {
                        $user->email_verified_at = Date('Y-m-d H:i:s');
                        $user->save();
                    }),
                Action::make('Unverify')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (User $user) {
                        $user->email_verified_at = null;
                        $user->save();
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
