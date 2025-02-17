<?php

namespace App\Filament\Pages;

use App\Livewire\SelectMesaModal;
use App\Models\Caja;
use App\Models\Comanda;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Mail\Mailables\Content;
use Livewire\Attributes\On;
use Livewire\Livewire;

class CrearComanda extends Page
{
    use HasPageShield;
    public $mesaSeleccionada = null;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.crear-comanda';

    public function getTitle(): string  // Cambiamos a getTitle() que es el método correcto
    {
        $nextId = Comanda::latest('id')->first()?->id ?? 0;
        return 'COMANDA N° ' . ($nextId + 1);
    }
}
