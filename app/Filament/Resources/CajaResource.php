<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CajaResource\Pages;
use App\Filament\Resources\CajaResource\RelationManagers;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\SesionCaja;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CajaResource extends Resource
{
    protected static ?string $model = Caja::class;

    protected static ?string $navigationGroup = 'Caja';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->autocomplete(false),
                Forms\Components\Select::make('sucursal_id')
                    ->relationship('sucursal', 'nombre')
                    ->required(),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('saldo_actual')
                            ->prefix('S/.')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),

                        Forms\Components\Select::make('estado')
                            ->required()
                            ->options(
                                [
                                    'Abierta' => 'Abierta',
                                    'Cerrada' => 'Cerrada',
                                    'Deshabilitada' => 'Deshabilitada',
                                ]
                            )
                            ->default('Cerrada'),

                    ])->columns(3),
                CheckboxList::make('zonas')
                    ->relationship('zonas',  'nombre',  function ($query) {
                        $query->where('estado', true); // Filtra las mesas cuyo estado es true
                    })
                    ->searchable()
                    ->columns(3),
                Forms\Components\Select::make('users')
                    ->label('Cajeros')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->preload()
                    ->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre'),
                Tables\Columns\TextColumn::make('saldo_actual')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'success' => 'Abierta',
                        'danger' => 'Cerrada',
                        'gray' => 'Deshabilitada',
                    ]),
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
                Tables\Actions\EditAction::make(),
                // Acción de cierre de caja
                Tables\Actions\Action::make('cerrarCaja')
                    ->label('Cerrar Caja')
                    ->color('danger')
                    ->icon('heroicon-o-lock-closed')
                    ->requiresConfirmation()
                    ->modalHeading('Cerrar Caja')
                    ->modalDescription('¿Estás seguro de que deseas cerrar esta caja? Esta acción calculará el saldo de cierre y cerrará la sesión activa.')
                    ->modalSubmitActionLabel('Sí, cerrar caja')
                    ->visible(function (Caja $record) {
                        // Solo mostrar para cajas abiertas
                        return $record->estado === 'Abierta';
                    })
                    ->action(function (Caja $caja) {
                        // Aquí va la lógica de cierre de caja
                        $sesionActiva = SesionCaja::where('caja_id', $caja->id)
                            ->where('estado', true)
                            ->latest()
                            ->first();

                        if (!$sesionActiva) {
                            Notification::make()
                                ->title('No hay sesión activa')
                                ->body('No se encontró una sesión activa para esta caja.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Obtener movimientos de la sesión
                        $movimientos = MovimientoCaja::where('sesion_caja_id', $sesionActiva->id)->get();

                        // Calcular saldo de cierre
                        $ingresos = $movimientos->where('tipo_transaccion', 'Ingreso')->sum('monto');
                        $egresos = $movimientos->where('tipo_transaccion', 'Egreso')->sum('monto');
                        $saldoCierre = $sesionActiva->saldo_inicial + $ingresos - $egresos;

                        // Actualizar sesión
                        $sesionActiva->fecha_cierra = now()->format('Y-m-d H:i:s');
                        $sesionActiva->saldo_cierre = $saldoCierre;
                        $sesionActiva->estado = false;
                        $sesionActiva->save();

                        // Actualizar caja
                        $caja->estado = 'Cerrada';
                        $caja->saldo_actual = $saldoCierre;
                        $caja->save();

                        // Eliminar las cookies de sesión relacionadas con la caja
                        \Illuminate\Support\Facades\Session::forget('caja_seleccionada');
                        \Illuminate\Support\Facades\Session::forget('sesion_caja_id');

                        Notification::make()
                            ->title('Caja cerrada correctamente')
                            ->body("Saldo de cierre: S/. " . number_format($saldoCierre, 2))
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListCajas::route('/'),
            'create' => Pages\CreateCaja::route('/create'),
            'edit' => Pages\EditCaja::route('/{record}/edit'),
        ];
    }
}
