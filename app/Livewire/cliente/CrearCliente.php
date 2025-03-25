<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\TipoDocumento;
use Filament\Notifications\Notification;
use GuzzleHttp\Client;
use Livewire\Attributes\On;
use Livewire\Component;

class CrearCliente extends Component
{


    public $isOpen = false;
    public $nombre, $tipo_documento_id, $numero_documento, $edad, $telefono, $email, $direccion;

    protected $rules = [
        'tipo_documento_id' => 'required',
        'numero_documento' => 'required|min:8',

    ];

    protected $messages = [
        'tipo_documento_id.required' => 'Selecione un tipo de documento',
        'numero_documento.required' => 'El número de documento es obligatorio.',
        'numero_documento.digits' => 'El número de documento debe tener al menos 8 dígitos.',
    ];

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->resetInputFields();
        $this->isOpen = false;
    }

    public function saveClient()
    {
        $this->validate();

        try {
            // Verificamos si existe el número de documento
            $clienteExistente = Cliente::where('numero_documento', $this->numero_documento)->first();

            if ($clienteExistente) {
                Notification::make()
                    ->title('Documento Duplicado')
                    ->body('El número de documento ' . $this->numero_documento . ' ya está registrado en el sistema.')
                    ->danger()
                    ->duration(5000)
                    ->send();
                return;
            }

            Cliente::create([
                'nombre' => $this->nombre,
                'tipo_documento_id' => $this->tipo_documento_id,
                'numero_documento' => $this->numero_documento,
                'edad' => $this->edad ?: null,
                'telefono' => $this->telefono ?: null,
                'email' => $this->email ?: null,
                'direccion' => $this->direccion ?: null,
            ]);

            Notification::make()
                ->title('Cliente guardado exitosamente')
                ->success()
                ->send();

            $this->resetInputFields();
            $this->dispatch('clientSaved');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al guardar el cliente')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function resetInputFields()
    {
        $this->nombre = '';
        $this->tipo_documento_id = '';
        $this->numero_documento = '';
        $this->edad = '';
        $this->telefono = '';
        $this->email = '';
        $this->direccion = '';
    }



    #[On('numeroDocumentoActualizado')]
    public function actualizarNumeroDocumento($numero)
    {
        $this->numero_documento = $numero;
    }

    #[On('numeroDocumentoBuscar')]
    public function actualizarNumeroDocumentoVentaDirecta($numero)
    {
        $this->numero_documento = $numero;
    }

    public function render()
    {
        return view('livewire.cliente.crear-cliente', [
            'tipos_documentos' => TipoDocumento::where('estado', 1)->get()
        ]);
    }

    //Busqueda de documento
    public function buscarDocumento()
    {
        $this->validate();

        $longitud = strlen($this->numero_documento);

        if ($longitud === 8) {
            // Búsqueda por DNI
            $this->buscarPorDNI();
        } else {
            // Búsqueda por RUC
            $this->buscarPorRUC();
        }
    }

    private function buscarPorDNI()
    {
        try {
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
                'query' => ['numero' => $this->numero_documento]
            ];

            // Realizar la solicitud
            $res = $client->request('GET', '/v2/reniec/dni', $parameters);
            $response = json_decode($res->getBody()->getContents(), true);

            // Verificar y establecer datos en el formulario
            if (isset($response['numeroDocumento'])) {
                $this->nombre = $response['nombres'] . ' ' . $response['apellidoPaterno'] . ' ' . $response['apellidoMaterno'];
                Notification::make()
                    ->title('DNI encontrado')
                    ->body('Se encontraron los datos del DNI correctamente')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('DNI no encontrado')
                    ->body('No se encontraron datos para este número de DNI')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar con el servicio de RENIEC: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // private function buscarPorRUC()
    // {
    //     try {
    //         $token = config('services.servicio_sunat.key');
    //         $client = new Client(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);

    //         $parameters = [
    //             'http_errors' => false,
    //             'connect_timeout' => 5,
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $token,
    //                 'Referer' => 'http://apis.net.pe/api-ruc',
    //                 'User-Agent' => 'laravel/guzzle',
    //                 'Accept' => 'application/json',
    //             ],
    //             'query' => ['numero' => $this->numero_documento]
    //         ];

    //         // Realizar la solicitud
    //         $res = $client->request('GET', '/v2/sunat/ruc', $parameters);
    //         $response = json_decode($res->getBody()->getContents(), true);

    //         // Verificar y establecer datos en el formulario
    //         if (isset($response['numeroDocumento'])) {
    //             $this->nombre = $response['nombre'] ?? ($response['razonSocial'] ?? 'No disponible');

    //             Notification::make()
    //                 ->title('RUC encontrado')
    //                 ->body('Se encontraron los datos del RUC correctamente')
    //                 ->success()
    //                 ->send();
    //         } else {
    //             Notification::make()
    //                 ->title('RUC no encontrado')
    //                 ->body('No se encontraron datos para este número de RUC')
    //                 ->warning()
    //                 ->send();
    //         }
    //     } catch (\Exception $e) {
    //         Notification::make()
    //             ->title('Error de conexión')
    //             ->body('No se pudo conectar con el servicio de SUNAT: ' . $e->getMessage())
    //             ->danger()
    //             ->send();
    //     }
    // }


    public function buscarPorRUC()
    {
        try {
            $token = config('services.servicio_sunat.key');
            $number = $this->numero_documento;

            // Usar GuzzleHttp exactamente como en tu código
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.apis.net.pe', 'verify' => false]);

            $parameters = [
                'http_errors' => false,
                'connect_timeout' => 5,
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Referer' => 'https://apis.net.pe/api-consulta-ruc',
                    'User-Agent' => 'laravel/guzzle',
                    'Accept' => 'application/json',
                ],
                'query' => ['numero' => $number]
            ];

            $res = $client->request('GET', '/v2/sunat/ruc', $parameters);
            $response = json_decode($res->getBody()->getContents(), true);

            // Opcional: Registrar la respuesta completa para depuración
            // \Log::info('Respuesta API SUNAT', $response);

            // Verificar y establecer la razón social
            if (isset($response['numeroDocumento'])) {
                $this->nombre = $response['nombre'] ?? ($response['razonSocial'] ?? 'No disponible');

                // Notificación de éxito usando Filament
                \Filament\Notifications\Notification::make()
                    ->title('RUC encontrado')
                    ->body('Se encontró la razón social correctamente')
                    ->success()
                    ->send();
            } else {
                // Si no se encontró información
                \Filament\Notifications\Notification::make()
                    ->title('RUC no encontrado')
                    ->body('No se encontraron datos para este número de RUC')
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            // Manejar cualquier error con notificación de Filament
            \Filament\Notifications\Notification::make()
                ->title('Error de conexión')
                ->body('No se pudo conectar con el servicio de SUNAT: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
