<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Mesa;
use App\Models\Zona;
use Livewire\Component;

class PanelMesas extends Component
{


    public $zonas;
    public $zonaSeleccionada;

    public function mount()
    {
        $this->zonas = Zona::with('mesas')->get();
        $this->zonaSeleccionada = null;
    }

    public function seleccionarZona($zonaId)
    {
        $this->zonaSeleccionada = $zonaId;
    }

    public function cambiarEstadoMesa($mesaId)
    {
        $mesa = Mesa::find($mesaId);

        if ($mesa->estado === 'Libre') {
            $mesa->estado = 'Ocupada';
        } elseif ($mesa->estado === 'Ocupada') {
            $mesa->estado = 'Inhabilitada';
        } else {
            $mesa->estado = 'Libre';
        }

        $mesa->save();
    }


    public function render()
    {
        return view('livewire.panel-mesas');
    }
}
