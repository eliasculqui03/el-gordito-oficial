<?php

namespace App\Livewire\Comanda;

use App\Models\Comanda;
use Livewire\Component;

class EditarComanda extends Component
{
    public $comandaId;
    public $comanda;

    // Otras propiedades necesarias para editar...

    public function mount($comandaId)
    {
        $this->comandaId = $comandaId;
        // $this->cargarComanda();
    }

    protected function cargarComanda()
    {
        $this->comanda = Comanda::with(['platosComanda', 'existenciasComanda'])
            ->find($this->comandaId);

        if (!$this->comanda) {
            $this->dispatch('notify', ['message' => 'Comanda no encontrada', 'type' => 'error']);
            return;
        }

        // Cargar datos para edición
    }

    public function guardarCambios()
    {
        // Validación y guardado

        // Notificar al componente padre
        $this->dispatch('comandaActualizada');
    }

    public function cancelar()
    {
        $this->dispatch('comandaActualizada');
    }


    public function render()
    {
        return view('livewire.comanda.editar-comanda');
    }
}
