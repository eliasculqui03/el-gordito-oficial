<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;

class SelectClientModal extends Component
{
    public $isOpen = false;
    public $search = '';
    public $selectedClientId;

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset('search');
    }

    public function selectClient($clientId)
    {
        $this->selectedClientId = $clientId;
        $this->closeModal();
    }




    public function render()
    {
        $clients = Cliente::where('nombre', 'like', '%' . $this->search . '%')->get();

        return view('livewire.select-client-modal', [
            'clients' => $clients,
        ]);
    }
}
