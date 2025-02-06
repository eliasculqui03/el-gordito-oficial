<?php

namespace App\Livewire;

use App\Models\Cliente;
use Filament\Notifications\Notification;
use Livewire\Component;

class BuscarCliente extends Component
{


    public $numero_documento;
    public $nombre;

    public function updatedNumeroDocumento($value)
    {

        $this->dispatch('numeroDocumentoActualizado', numero: $value);
    }

    public function buscar()
    {
        $cliente = Cliente::where('numero_documento', $this->numero_documento)->first();

        if ($cliente) {
            $this->nombre = $cliente->nombre;
            Notification::make()
                ->title('Cliente encontrado')
                ->success()
                ->send();
        } else {
            $this->nombre = '';
            Notification::make()
                ->title('Cliente no encontrado')
                ->danger()
                ->send();
        }
    }




    public function render()
    {
        return view('livewire.buscar-cliente');
    }
}
