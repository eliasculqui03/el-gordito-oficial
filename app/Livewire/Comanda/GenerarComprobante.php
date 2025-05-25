<?php

namespace App\Livewire\Comanda;

use App\Models\Comanda;
use Livewire\Component;

class GenerarComprobante extends Component
{
    public $comandaId;
    public $comanda;

    // Propiedades para el comprobante...

    public function mount($comandaId)
    {
        $this->comandaId = $comandaId;
        //$this->cargarComanda();
    }

    protected function cargarComanda()
    {
        $this->comanda = Comanda::with(['platosComanda', 'existenciasComanda', 'cliente'])
            ->find($this->comandaId);

        if (!$this->comanda) {
            $this->dispatch('notify', ['message' => 'Comanda no encontrada', 'type' => 'error']);
            return;
        }

        // Inicializar datos del comprobante
    }

    public function generarComprobante()
    {
        // Validación y generación

        // Notificar al componente padre
        $this->dispatch('comprobanteGenerado');
    }

    public function cancelar()
    {
        $this->dispatch('comprobanteGenerado');
    }

    public function render()
    {
        return view('livewire.comanda.generar-comprobante');
    }
}
