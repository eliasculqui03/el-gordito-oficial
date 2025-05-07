<?php

namespace App\Filament\Pages;

use App\Livewire\NotasModal;
use App\Models\SolicitudCompra;
use App\Models\NotaSolicitudCompra;
use App\Models\Proveedor;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;

class AprobacionSolicitudes extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string $view = 'filament.pages.aprobacion-solicitudes';
    protected static ?string $navigationLabel = 'Aprobación de Solicitudes';
    protected static ?string $title = 'Aprobación de Solicitudes';

    protected static ?string $slug = 'aprobacion-solicitudes';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }


    public function table(Table $table): Table
    {
        $userEmail = auth()->user()->email;
        $query = SolicitudCompra::query()->where('estado', 'Pendiente')->orderBy('created_at', 'desc');;

        // Verificar si el usuario es un proveedor
        $proveedor = Proveedor::where('email', $userEmail)->first();

        if ($proveedor) {
            // Si es proveedor, mostrar solo sus solicitudes
            $query->where('proveedor_id', $proveedor->id);
        }

        return $table
            ->query(
                $query
            )
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                //     ->label('ID')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('proveedor.nombre')
                    ->label('Proveedor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Solicitante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->label('Fecha Entrega')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('existencia.nombre')
                    ->label('Existencia'),
                Tables\Columns\TextColumn::make('existencia.unidadMedida.simbolo')
                    ->label('U. de medida'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0, '.', ',');
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),

                // Tables\Columns\TextColumn::make('estado')
                //     ->label('Estado')
                //     ->badge()
                //     ->color(fn(string $state): string => match ($state) {
                //         'Pendiente' => 'warning',
                //         default => 'gray',
                //     }),

            ])
            ->actions([
                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->form([
                        Textarea::make('nota')
                            ->label('Nota de Aprobación')
                            ->required()
                            ->maxLength(255)
                    ])
                    ->action(function (SolicitudCompra $record, array $data): void {
                        // Crear nueva nota
                        NotaSolicitudCompra::create([
                            'solicitud_compra_id' => $record->id,
                            'nota' => $data['nota'],
                            'motivo' => 'Aprobación'
                        ]);

                        // Actualizar estado de la solicitud
                        $record->update(['estado' => '2']);

                        Notification::make()
                            ->title('Solicitud Aprobada')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                //->visible(fn(SolicitudCompra $record): bool => $record->estado === 'Pendiente'),

                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->form([
                        Textarea::make('nota')
                            ->label('Motivo del Rechazo')
                            ->required()
                            ->maxLength(255)
                    ])
                    ->action(function (SolicitudCompra $record, array $data): void {
                        // Crear nueva nota
                        NotaSolicitudCompra::create([
                            'solicitud_compra_id' => $record->id,
                            'nota' => $data['nota'],
                            'motivo' => 'Rechazo'
                        ]);

                        // Actualizar estado de la solicitud
                        $record->update(['estado' => '3']);

                        Notification::make()
                            ->title('Solicitud Rechazada')
                            ->danger()
                            ->send();
                    })
                    //->visible(fn(SolicitudCompra $record): bool => $record->estado === 'Pendiente')
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('view_notes')
                    ->label('Ver Notas')
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
            //->defaultSort('created_at', 'desc')
            ->poll('5s');
    }
}
