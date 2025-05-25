<?php

namespace App\Livewire;

use App\Models\Comanda;
use App\Models\Caja;
use Livewire\Component;
use Livewire\WithPagination;

class GestionComandas extends Component
{
    use WithPagination;

    // Estados para controlar los modales
    public $modalComandas = false;
    public $modalEditar = false;
    public $modalComprobante = false;

    // ID de la caja
    public $cajaId;
    public $caja;

    // Filtros y búsqueda
    public $searchTerm = '';
    public $searchBy = 'nombre';
    public $filtroEstado = '';

    // ID de la comanda seleccionada
    public $comandaId = null;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'searchBy' => ['except' => 'nombre'],
        'filtroEstado' => ['except' => ''],
    ];

    protected $listeners = [
        'comandaActualizada' => 'handleComandaActualizada',
        'comprobanteGenerado' => 'handleComprobanteGenerado'
    ];

    public function mount($cajaId)
    {
        $this->cajaId = $cajaId;
        $this->caja = Caja::find($cajaId);
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    // Métodos para abrir/cerrar modales
    public function abrirModalComandas()
    {
        $this->modalComandas = true;
    }

    public function cerrarModalComandas()
    {
        $this->modalComandas = false;
    }

    public function editarComanda($id)
    {
        $this->comandaId = $id;
        $this->modalComandas = false;
        $this->modalEditar = true;
    }

    public function cerrarModalEditar()
    {
        $this->modalEditar = false;
        $this->comandaId = null;
        $this->modalComandas = true; // Volver a abrir el modal de comandas
    }

    public function generarComprobante($id)
    {
        $this->comandaId = $id;
        $this->modalComandas = false;
        $this->modalComprobante = true;
    }

    public function cerrarModalComprobante()
    {
        $this->modalComprobante = false;
        $this->comandaId = null;
        $this->modalComandas = true; // Volver a abrir el modal de comandas
    }

    // Handlers para eventos
    public function handleComandaActualizada()
    {
        $this->cerrarModalEditar();
        $this->dispatch('notify', ['message' => 'Comanda actualizada correctamente', 'type' => 'success']);
    }

    public function handleComprobanteGenerado()
    {
        $this->cerrarModalComprobante();
        $this->dispatch('notify', ['message' => 'Comprobante generado correctamente', 'type' => 'success']);
    }

    public function render()
    {
        $comandas = [];
        $estados = ['Abierta', 'Procesando', 'Completada', 'Cancelada'];

        // Solo cargar datos si el modal de comandas está abierto
        if ($this->modalComandas) {
            $comandasQuery = Comanda::query()
                ->with(['cliente', 'zona', 'mesa'])
                ->where('caja_id', $this->cajaId); // Filtrar por caja_id

            if ($this->filtroEstado) {
                $comandasQuery->where('estado', $this->filtroEstado);
            }

            if ($this->searchTerm) {
                $comandasQuery->whereHas('cliente', function ($query) {
                    if ($this->searchBy === 'nombre') {
                        $query->where('nombre', 'like', '%' . $this->searchTerm . '%');
                    } else {
                        $query->where('numero_documento', 'like', '%' . $this->searchTerm . '%');
                    }
                });
            }

            $comandas = $comandasQuery->orderBy('created_at', 'desc')
                ->paginate(5);
        }

        return view('livewire.gestion-comandas', [
            'comandas' => $comandas,
            'estados' => $estados,
            'caja' => $this->caja
        ]);
    }
}
