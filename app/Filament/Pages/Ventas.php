<?php

namespace App\Filament\Pages;

use App\Models\Venta;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Testing\Fluent\Concerns\Has;

class Ventas extends Page implements HasTable
{
    use InteractsWithTable, HasPageShield;

    protected static string $view = 'filament.pages.ventas';

    protected static ?string $navigationGroup = 'Ventas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Gestión de Ventas';
    protected static ?string $title = 'Gestión de Ventas';
    protected static ?string $slug = 'ventas';
    protected static ?int $navigationSort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(Venta::query())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('tipoComprobante.descripcion')
                    ->label('Tipo Comprobante')
                    ->sortable(),
                TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->sortable(),
                TextColumn::make('cliente.numero_documento')
                    ->label('Documento')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('metodo_pago')
                    ->label('Método Pago')
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('documento')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('documento')
                            ->label('Documento del cliente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['documento'] ?? null,
                                fn(Builder $query, $documento): Builder => $query->whereHas(
                                    'cliente',
                                    fn(Builder $query) => $query->where('numero_documento', 'like', "%{$documento}%")
                                )
                            );
                    }),
            ])
            ->actions([
                // \Filament\Tables\Actions\ViewAction::make()
                //     ->url(fn(Venta $record): string => route('filament.resources.ventas.view', $record)),
            ]);
    }

    // Si estás usando Filament 2.x, también debes implementar este método para la interfaz HasTable
    public function getTableQuery(): Builder
    {
        return Venta::query();
    }

    public function getTableColumns(): array
    {
        return [];
    }

    public function getTableFilters(): array
    {
        return [];
    }

    public function getTableActions(): array
    {
        return [];
    }

    public function getTableBulkActions(): array
    {
        return [];
    }
}
