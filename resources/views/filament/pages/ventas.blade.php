{{-- <x-filament-panels::page>
    <div class="py-4">
        <livewire:lista-comprobantes />
    </div>

    <!-- Componente de impresión que funciona como modal -->
    <livewire:imprimir-comprobante />
</x-filament-panels::page> --}}

<!-- resources/views/filament/pages/comprobantes-ticket.blade.php -->
<x-filament-panels::page>
    {{ $this->table }}
</x-filament-panels::page>
