<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitudCompraResource\Pages;
use App\Filament\Resources\SolicitudCompraResource\RelationManagers;
use App\Models\NotaSolicitudCompra;
use App\Models\SolicitudCompra;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SolicitudCompraResource extends Resource
{
    protected static ?string $model = SolicitudCompra::class;

    protected static ?string $navigationGroup = 'Compras';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Información general')
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema(fn(string $operation) => match ($operation) {
                                'view' => [
                                    Placeholder::make('usuario')
                                        ->content(function ($record) {
                                            // Busca el usuario por el user_id del registro
                                            $user = \App\Models\User::find($record->user_id);
                                            return $user ? $user->name : 'Usuario no encontrado';
                                        })
                                        ->label('Usuario'),
                                ],
                                default => [
                                    Hidden::make('user_id')
                                        ->default(auth()->id())
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function (Hidden $component) {
                                            $component->state(auth()->id());
                                        }),
                                    Placeholder::make('usuario')
                                        ->content(auth()->user()->name)
                                        ->label('Usuario'),
                                ],
                            }),
                        Forms\Components\Select::make('proveedor_id')
                            ->relationship('proveedor', 'nombre', function ($query) {
                                return $query->where('estado', true);
                            })
                            ->required(),
                        Forms\Components\DateTimePicker::make('fecha_entrega')
                            ->required(),

                        Forms\Components\Select::make('estado')
                            ->options([
                                'Pendiente' => 'Pendiente',
                                'Aprobada' => 'Aprobada',
                                'Rechazada' => 'Rechazada',
                                'Cancelada' => 'Cancelada'
                            ])
                            ->required()
                            ->visible(fn($context) => $context === 'edit'),


                    ])->columnS(4),


                Section::make('')
                    ->schema([
                        Forms\Components\Select::make('existencia_id')
                            ->relationship('existencia', 'nombre', function ($query) {
                                return $query->where('estado', true);
                            })
                            ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nombre} - {$record->unidadMedida->descripcion}")
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) { // añadido $get aquí
                                if ($state) {
                                    $existencia = \App\Models\Existencia::find($state);
                                    if ($existencia) {
                                        $set('precio_compra', $existencia->precio_compra);
                                        // Usar $get en lugar de $this->data
                                        $cantidad = $get('cantidad') ?? 1;
                                        $set('total', $existencia->precio_compra * $cantidad);
                                    }
                                }
                            })
                            ->searchable()
                            ->preload(),




                    ])->columnSpan(2),

                Section::make('')

                    ->schema([
                        Forms\Components\TextInput::make('precio_compra')
                            ->label('Precio compra')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix('S/.'),
                        Forms\Components\TextInput::make('cantidad')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live()
                            ->rules(['min:1'])
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $precio = $get('precio_compra');
                                if ($precio && is_numeric($state)) {
                                    $total = round($precio * $state, 2); // Redondear a 2 decimales
                                    $set('total', $total);
                                }
                            }),

                        Forms\Components\TextInput::make('total')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix('S/.')
                            ->step('0.01') // Permite decimales
                            ->inputMode('decimal'), // Mejor para entrada numérica
                    ])->columns(3)
                    ->columnSpan(2)


            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->query(SolicitudCompra::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('proveedor.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('existencia.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0, '.', ',');
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),
                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'primary' => 'Pendiente',
                        'info' => 'Aprobada',
                        'danger' => 'Rechazada',
                        'gray' => 'Cancelada',
                        'success' => 'Pagada',
                    ]),
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
            ])
            ->recordUrl(null)
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Aprobada' => 'Aprobada',
                        'Rechazada' => 'Rechazada',
                        'Cancelada' => 'Cancelada',
                    ]),

            ])
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
                Tables\Actions\Action::make('view_notes')
                    ->label('Ver Notas')
                    ->color('info')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->modalHeading('Notas de la Solicitud')
                    ->form([
                        Textarea::make('nueva_nota')
                            ->label('Agregar Nueva Nota')
                            ->required(),
                    ])
                    ->action(function (SolicitudCompra $record, array $data): void {
                        // Crear nueva nota
                        NotaSolicitudCompra::create([
                            'solicitud_compra_id' => $record->id,
                            'nota' => $data['nueva_nota'],
                            'motivo' => 'Nota' // false para notas normales
                        ]);

                        Notification::make()
                            ->title('Nota agregada correctamente')
                            ->success()
                            ->send();
                    })
                    ->modalContent(fn(SolicitudCompra $record) => view('filament.pages.components.notas-modal', [
                        'notas' => $record->notaSolicitudCompra()
                            ->latest()
                            ->get()
                    ]))
                    ->modalWidth('lg')

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ])
        ;
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
            'index' => Pages\ListSolicitudCompras::route('/'),
            'create' => Pages\CreateSolicitudCompra::route('/create'),
            'edit' => Pages\EditSolicitudCompra::route('/{record}/edit'),
        ];
    }
}
