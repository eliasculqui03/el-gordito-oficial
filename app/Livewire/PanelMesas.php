<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Mesa;
use App\Models\Zona;
use Livewire\Component;

class PanelMesas extends Component
{

    public $cajaActual = null;
    public $zonaActual = null;
    public $cajas;
    public $zonas;

    const ESTADOS = [
        'Libre' => 'Libre',
        'Ocupada' => 'Ocupada',
        'Inhabilitada' => 'Inhabilitada'
    ];

    public function mount()
    {
        $this->cajas = Caja::where('estado', true)
            ->with(['zonas' => function ($query) {
                $query->where('estado', true)->with('mesas');
            }])
            ->get();

        $this->cajaActual = $this->cajas->first()?->id;
        $this->actualizarZonas();
    }

    public function cambiarCaja($cajaId)
    {
        $this->cajaActual = $cajaId;
        $this->actualizarZonas();
        $this->zonaActual = $this->zonas->first()?->id;
    }

    public function actualizarZonas()
    {
        if ($this->cajaActual) {
            $this->zonas = Zona::with('mesas')
                ->where('caja_id', $this->cajaActual)
                ->where('estado', true)
                ->get();
        }
    }

    public function cambiarZona($zonaId)
    {
        $this->zonaActual = $zonaId;
    }

    public function cambiarEstadoMesa(Mesa $mesa)
    {
        $estados = array_keys(self::ESTADOS);
        $estadoActual = array_search($mesa->estado, $estados);
        $siguienteEstado = $estados[($estadoActual + 1) % count($estados)];

        $mesa->update([
            'estado' => $siguienteEstado
        ]);

        $this->actualizarZonas();
    }
    public function render()
    {
        return view('livewire.panel-mesas');
    }
}
