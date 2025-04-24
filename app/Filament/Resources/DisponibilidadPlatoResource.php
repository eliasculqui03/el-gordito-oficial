<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisponibilidadPlatoResource\Pages;
use App\Filament\Resources\DisponibilidadPlatoResource\RelationManagers;
use App\Models\DisponibilidadPlato;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;

class DisponibilidadPlatoResource extends Resource
{
    protected static ?string $model = DisponibilidadPlato::class;

    protected static ?string $navigationLabel = 'Disponbilidad platos';
    protected static ?string $label = 'disponibilidad';
    protected static ?string $pluralLabel = 'Disponbilidad';

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('plato_id')
                    ->relationship('plato', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'La existencia ya tiene disponibilidad.'
                    ]),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('disponibilidad')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plato.nombre')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric(),
                Tables\Columns\ToggleColumn::make('disponibilidad')
                    ->label('Disponibilidad')
                    ->onColor(Color::Green)
                    ->offColor(Color::Red)
                    ->afterStateUpdated(function (DisponibilidadPlato $record, bool $state): void {
                        // Si se desactiva la disponibilidad y hay cantidad, mostrar notificación informativa
                        if (!$state && $record->cantidad > 0) {
                            Notification::make()
                                ->title('Disponibilidad desactivada')
                                ->body('El plato ha sido desactivado aunque aún hay ' . $record->cantidad . ' unidades disponibles.')
                                ->info()
                                ->send();
                        }
                    }),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('increment')
                    ->label('Incrementar')
                    ->icon('heroicon-o-plus')
                    ->color(Color::Green)
                    ->requiresConfirmation()
                    ->modalHeading('Incrementar disponibilidad')
                    ->modalDescription('¿Cuántos platos deseas agregar?')
                    ->form([
                        Forms\Components\TextInput::make('cantidad_incremento')
                            ->label('Cantidad a incrementar')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                    ])
                    ->action(function (DisponibilidadPlato $record, array $data): void {
                        $record->cantidad += $data['cantidad_incremento'];
                        $record->save();

                        Notification::make()
                            ->title('Disponibilidad incrementada')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('decrement')
                    ->label('Decrementar')
                    ->icon('heroicon-o-minus')
                    ->color(Color::Red)
                    ->requiresConfirmation()
                    ->modalHeading('Decrementar disponibilidad')
                    ->modalDescription('¿Cuántos platos deseas restar?')
                    ->form([
                        Forms\Components\TextInput::make('cantidad_decremento')
                            ->label('Cantidad a decrementar')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                    ])
                    ->action(function (DisponibilidadPlato $record, array $data): void {
                        // Verificar que no se vaya a números negativos
                        if ($record->cantidad < $data['cantidad_decremento']) {
                            Notification::make()
                                ->title('Error al decrementar')
                                ->body('No puedes restar más platos de los disponibles.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->cantidad -= $data['cantidad_decremento'];

                        // Si la cantidad llega a cero, desactivar la disponibilidad
                        if ($record->cantidad <= 0) {
                            $record->disponibilidad = false;
                        }

                        $record->save();

                        Notification::make()
                            ->title('Disponibilidad decrementada')
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDisponibilidadPlatos::route('/'),
        ];
    }
}
