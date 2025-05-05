<?php

namespace App\Livewire;

use App\Models\ComprobantePago;
use Livewire\Component;
use Livewire\WithPagination;

class ListaComprobantes extends Component
{
    use WithPagination;

    public $search = '';

    public function updating($property)
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }

    public function abrirModalImprimir($id)
    {
        $this->dispatch('abrirModalImprimir', $id);
    }

    public function render()
    {
        $comprobantes = ComprobantePago::query()
            ->with(['tipoComprobante', 'cliente', 'comanda', 'user', 'caja'])
            ->when($this->search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->whereHas('cliente', function ($query) use ($search) {
                        $query->where('nombre', 'like', "%{$search}%");
                    })
                        ->orWhere('serie', 'like', "%{$search}%")
                        ->orWhere('numero', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.lista-comprobantes', [
            'comprobantes' => $comprobantes
        ]);
    }
}
