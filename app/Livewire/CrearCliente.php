<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\TipoDocumento;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class CrearCliente extends Component
{


    public $isOpen = false;
    public $nombre, $tipo_documento_id, $numero_documento, $edad, $telefono, $email, $direccion;

    protected $rules = [
        'nombre' => 'required',
        'tipo_documento_id' => 'required',
        'numero_documento' => 'required',

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
                'edad' => $this->edad,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'direccion' => $this->direccion,
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


    public function searchDocument()
    {
        if (!empty($this->numero_documento)) {
            $cliente = Cliente::where('numero_documento', $this->numero_documento)->first();
            if ($cliente) {
                Notification::make()
                    ->title('Cliente encontrado')
                    ->warning()
                    ->send();
            }
        }
    }
    public function render()
    {
        return view('livewire.crear-cliente', [
            'tipos_documentos' => TipoDocumento::where('estado', 1)->get()
        ]);
    }
}
