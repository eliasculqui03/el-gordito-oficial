<?php

namespace App\Filament\Pages;

use App\Livewire\SelectMesaModal;
use App\Models\Caja;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Mail\Mailables\Content;
use Livewire\Attributes\On;
use Livewire\Livewire;

class CrearComanda extends Page
{
    public $mesaSeleccionada = null;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.crear-comanda';
}
