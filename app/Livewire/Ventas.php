<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Comanda;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ventas extends Component
{


    public function render()
    {
        return view('livewire.ventas');
    }
}
