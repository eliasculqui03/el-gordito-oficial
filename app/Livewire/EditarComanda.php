<?php

namespace App\Livewire;

use App\Models\Comanda;
use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class EditarComanda extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $searchBy = 'nombre'; // búsqueda por nombre o número de documento
    public $filtroEstado = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'searchBy' => ['except' => 'nombre'],
        'filtroEstado' => ['except' => ''],
    ];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $comandasQuery = Comanda::query()
            ->with(['cliente', 'zona', 'mesa']);

        // Filtrar por estado si se ha seleccionado uno
        if ($this->filtroEstado) {
            $comandasQuery->where('estado', $this->filtroEstado);
        }

        // Búsqueda por cliente (nombre o número de documento)
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
            ->paginate(10);

        $estados = ['Abierta', 'Procesando', 'Completada', 'Cancelada'];

        return view('livewire.editar-comanda', [
            'comandas' => $comandas,
            'estados' => $estados
        ]);
    }

    public function editarComanda($comandaId)
    {
        return redirect()->route('comandas.edit', $comandaId);
    }

    public function generarComprobante($comandaId)
    {
        // Aquí iría la lógica para generar el comprobante
        // Por ejemplo, redirigir a una ruta específica
        return redirect()->route('comandas.comprobante', $comandaId);
    }
}
