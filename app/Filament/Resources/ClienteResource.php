<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('tipo_documento')
                    ->required()
                    ->options([
                        'DNI' => 'DNI',
                        'CE' => 'CE'
                    ]),
                Forms\Components\TextInput::make('numero_documento')
                    ->required()
                    ->maxLength(8)
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('buscarDni')
                            ->icon('heroicon-o-magnifying-glass')
                            ->action(function (Forms\Set $set, $state) {
                                if (strlen($state) === 8) {
                                    $token = 'apis-token-12378.4HGOhTQkQu5n4kj0Zl1qX1Un87malHiI';
                                    $client = new Client(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);

                                    $parameters = [
                                        'http_errors' => false,
                                        'connect_timeout' => 5,
                                        'headers' => [
                                            'Authorization' => 'Bearer ' . $token,
                                            'Referer' => 'https://apis.net.pe/api-consulta-dni',
                                            'User-Agent' => 'laravel/guzzle',
                                            'Accept' => 'application/json',
                                        ],
                                        'query' => ['numero' => $state]
                                    ];

                                    try {
                                        // Realizar la solicitud
                                        $res = $client->request('GET', '/v2/reniec/dni', $parameters);
                                        $response = json_decode($res->getBody()->getContents(), true);

                                        // Verificar y establecer datos en el formulario
                                        if (isset($response['numeroDocumento'])) {
                                            $set('nombre', $response['nombres'] . ' ' . $response['apellidoPaterno'] . ' ' . $response['apellidoMaterno']);
                                        } else {
                                            $set('nombre', 'No encontrado');
                                            $set('appaterno', 'No encontrado');
                                            $set('apmaterno', 'No encontrado');
                                        }
                                    } catch (\Exception $e) {
                                        // Manejar errores de la solicitud
                                        $set('nombre', 'Error al conectar');
                                    }
                                } else {
                                    // Mostrar un mensaje de error si el número de documento no es válido
                                    $set('nombre', 'Número de documento inválido');
                                }
                            })
                    ),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->readonly()
                    ->maxLength(255),
                Forms\Components\TextInput::make('edad')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('direccion')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('edad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
