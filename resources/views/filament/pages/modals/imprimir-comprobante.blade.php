<div>
    @if (isset($comprobanteId))
        <livewire:imprimir-comprobante :id="$comprobanteId" />
    @else
        <div class="p-4 text-red-500">
            No se ha seleccionado ningún comprobante para imprimir.
        </div>
    @endif
</div>
