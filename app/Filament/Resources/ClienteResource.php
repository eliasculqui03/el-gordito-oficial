<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('tipo_documento_id')
                    ->relationship('tipoDocumento', 'descripcion_corta')
                    ->required(),
                Forms\Components\TextInput::make('numero_documento')
                    ->required()
                    ->maxLength(11) // Cambiado a 11 para soportar RUC
                    ->hint('8 dígitos para DNI, 11 dígitos para RUC')
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('buscarDocumento')
                            ->icon('heroicon-o-magnifying-glass')
                            ->action(function (Forms\Set $set, $state) {
                                if (strlen($state) === 8) {
                                    // Búsqueda por DNI
                                    self::buscarPorDNI($set, $state);
                                } elseif (strlen($state) === 11) {
                                    // Búsqueda por RUC
                                    self::buscarPorRUC($set, $state);
                                } else {
                                    // Mensaje de error por longitud inválida
                                    Notification::make()
                                        ->title('Formato incorrecto')
                                        ->body('Debe ingresar 8 dígitos para DNI o 11 para RUC')
                                        ->danger()
                                        ->send();

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
                Tables\Columns\TextColumn::make('tipoDocumento.descripcion_corta')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                ExportBulkAction::make()
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


    /**
     * Realiza la búsqueda de persona por DNI
     */
    protected static function buscarPorDNI(Forms\Set $set, $state)
    {
        $token = config('services.servicio_reniec.key');
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

                // Notificación de éxito
                Notification::make()
                    ->title('DNI encontrado')
                    ->body('Se encontraron los datos correctamente')
                    ->success()
                    ->send();
            } else {
                $set('nombre', 'No encontrado');

                // Notificación de advertencia
                Notification::make()
                    ->title('DNI no encontrado')
                    ->body('No se encontraron datos para este número de DNI')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            // Manejar errores de la solicitud
            $set('nombre', 'Error al conectar');

            // Notificación de error
            Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar con el servicio: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Realiza la búsqueda de empresa por RUC
     */
    protected static function buscarPorRUC(Forms\Set $set, $state)
    {
        $token = config('services.servicio_sunat.key');
        $client = new Client(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);

        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Referer' => 'http://apis.net.pe/api-ruc',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ],
            'query' => ['numero' => $state]
        ];

        try {
            // Realizar la solicitud
            $res = $client->request('GET', '/v2/sunat/ruc', $parameters);
            $response = json_decode($res->getBody()->getContents(), true);

            // Verificar y establecer datos en el formulario
            if (isset($response['numeroDocumento'])) {
                // Para empresas, usar razón social
                $nombreEmpresa = $response['nombre'] ?? ($response['razonSocial'] ?? 'No disponible');
                $set('nombre', $nombreEmpresa);

                // Si existe el campo dirección, también lo completamos
                if (isset($response['direccion'])) {
                    $set('direccion', $response['direccion']);
                }

                // Notificación de éxito
                Notification::make()
                    ->title('RUC encontrado')
                    ->body('Se encontraron los datos correctamente')
                    ->success()
                    ->send();
            } else {
                $set('nombre', 'No encontrado');

                // Notificación de advertencia
                Notification::make()
                    ->title('RUC no encontrado')
                    ->body('No se encontraron datos para este número de RUC')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            // Manejar errores de la solicitud
            $set('nombre', 'Error al conectar');

            // Notificación de error
            Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar con el servicio: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
