<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SesionCajaResource\Pages;
use App\Filament\Resources\SesionCajaResource\RelationManagers;
use App\Models\SesionCaja;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class SesionCajaResource extends Resource
{
    protected static ?string $model = SesionCaja::class;

    protected static ?string $navigationGroup = 'Caja';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuarios')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('caja_id')
                    ->relationship('caja', 'nombre')
                    ->required(),
                Forms\Components\DateTimePicker::make('fecha_apertura')
                    ->required(),
                Forms\Components\DateTimePicker::make('fecha_cierra')
                    ->default(null),
                Forms\Components\TextInput::make('saldo_inicial')
                    ->prefix('S/.')
                    ->default(0)
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('saldo_cierre')
                    ->prefix('S/.')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Toggle::make('estado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('Usuario'),
                Tables\Columns\TextColumn::make('caja.nombre'),
                Tables\Columns\TextColumn::make('fecha_apertura')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('fecha_cierra')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('saldo_inicial')
                    ->numeric()->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),
                Tables\Columns\TextColumn::make('saldo_cierre')
                    ->numeric()->formatStateUsing(function ($state) {
                        return 'S/. ' . number_format($state, 2);
                    }),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                // Filter::make('fecha_apertura')
                //     ->form([
                //         Forms\Components\DatePicker::make('desde')
                //             ->label('Desde'),
                //         Forms\Components\DatePicker::make('hasta')
                //             ->label('Hasta'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['desde'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('fecha_apertura', '>=', $date),
                //             )
                //             ->when(
                //                 $data['hasta'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('fecha_apertura', '<=', $date),
                //             );
                //     }),
                Filter::make('created_at')

                    ->form([
                        DatePicker::make('created_at')
                            ->label('Buscar por fecha')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn(Builder $query, $date): Builder => $query
                                    ->whereDate('created_at', '=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),


            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSesionCajas::route('/'),
        ];
    }
}
