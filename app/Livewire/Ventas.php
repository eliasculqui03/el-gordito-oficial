<?php

namespace App\Livewire;

use App\Models\ComprobantePago;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Ventas extends Component
{

    use WithPagination;


    public $search = '';

    public function updating($property)
    {
        if ($property === 'search') {
            $this->resetPage();
        }
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
                        ->orWhere('numero', 'like', "%{$search}%")
                        ->orWhereHas('comanda', function ($query) use ($search) {
                            $query->where('numero', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.ventas', [
            'comprobantes' => $comprobantes
        ]);
    }
}
