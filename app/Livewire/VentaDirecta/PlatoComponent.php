<?php

namespace App\Livewire\VentaDirecta;

use App\Models\CategoriaPlato;
use App\Models\DisponibilidadPlato;
use App\Models\Plato;
use Filament\Notifications\Notification;
use Livewire\Component;

class PlatoComponent extends Component
{

    public $showModal = false;
    public $showConfirmation = false;
    public $selectedCategoria = '';
    public $search = '';
    public $platosSeleccionados = [];
    public $totalComanda = 0;

    protected $listeners = ['openPlatosModal' => 'openModal'];

    public function mount()
    {
        // Por defecto, no seleccionamos ninguna categoría (esto hará que se seleccione "Todas")
        $this->selectedCategoria = '';
    }

    public function render()
    {
        $categorias = CategoriaPlato::where('estado', true)->get();
        $platos = $this->getPlatos();

        return view('livewire.venta-directa.plato-component', [
            'categorias' => $categorias,
            'platos' => $platos
        ]);
    }


    public function getPlatos()
    {
        // Crear la consulta base
        $query = Plato::query()->where('estado', true);

        // Filtrar por categoría si está seleccionada
        if ($this->selectedCategoria) {
            $query->where('categoria_plato_id', $this->selectedCategoria);
        }

        // Buscar por nombre o descripción
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            });
        }

        // Incluir relaciones necesarias
        $query->with(['categoriaPlato', 'disponibilidadPlato']);

        // Obtener todos los resultados sin paginación
        return $query->get();
    }

    public function selectCategoria($categoriaId)
    {
        $this->selectedCategoria = $categoriaId;
    }

    public function openModal()
    {
        // Al abrir el modal, aseguramos que se muestre la opción "Todas" (cadena vacía)
        $this->selectedCategoria = '';
        $this->search = '';
        $this->platosSeleccionados = [];
        $this->totalComanda = 0;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->platosSeleccionados = [];
        $this->totalComanda = 0;
    }

    // Modificar el método agregarPlato para enviar el plato inmediatamente al padre
    public function agregarPlato($platoId, $esLlevar = false)
    {

        $plato = Plato::with(['categoriaPlato', 'disponibilidadPlato'])->find($platoId);

        if (!$plato) {
            return;
        }

        // Determinar el precio según si es para llevar o no
        $precioUnitario = $esLlevar && $plato->precio_llevar > 0 ? $plato->precio_llevar : $plato->precio;

        // Crear el objeto plato
        $platoData = [
            'plato_id' => $plato->id,
            'nombre' => $plato->nombre,
            'categoria' => $plato->categoriaPlato->nombre,
            'precio_unitario' => $precioUnitario,
            'cantidad' => 1,
            'es_llevar' => $esLlevar,
            'subtotal' => $precioUnitario
        ];

        // Emitir evento con el plato al componente padre
        $this->dispatch('platoAgregado', plato: $platoData);

        // Mostrar notificación de éxito
        Notification::make()
            ->title('Existencia agregada')
            ->body("Existencia {$plato->nombre} agregada")
            ->success()
            ->send();
    }

    // Método para incrementar la cantidad de un plato
    public function incrementarCantidad($indice)
    {
        if (!isset($this->platosSeleccionados[$indice])) {
            return;
        }

        // Verificar stock disponible antes de incrementar
        $plato = Plato::with(['disponibilidadPlato'])->find($this->platosSeleccionados[$indice]['plato_id']);

        if (
            $plato && $plato->disponibilidadPlato &&
            $plato->disponibilidadPlato->disponibilidad == 'Disponible' &&
            $this->platosSeleccionados[$indice]['cantidad'] < $plato->disponibilidadPlato->cantidad
        ) {

            $this->platosSeleccionados[$indice]['cantidad']++;
            $this->platosSeleccionados[$indice]['subtotal'] =
                $this->platosSeleccionados[$indice]['cantidad'] *
                $this->platosSeleccionados[$indice]['precio_unitario'];

            $this->calcularTotal();
        } else {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'No hay suficiente stock disponible'
            ]);
        }
    }

    // Método para decrementar la cantidad de un plato
    public function decrementarCantidad($indice)
    {
        if (!isset($this->platosSeleccionados[$indice])) {
            return;
        }

        if ($this->platosSeleccionados[$indice]['cantidad'] > 1) {
            $this->platosSeleccionados[$indice]['cantidad']--;
            $this->platosSeleccionados[$indice]['subtotal'] =
                $this->platosSeleccionados[$indice]['cantidad'] *
                $this->platosSeleccionados[$indice]['precio_unitario'];
        } else {
            // Si la cantidad es 1, remover el plato
            $this->removerPlato($indice);
        }

        $this->calcularTotal();
    }

    // Método para cambiar entre "para llevar" y "para mesa"
    public function toggleLlevar($indice)
    {
        if (!isset($this->platosSeleccionados[$indice])) {
            return;
        }

        $plato = Plato::find($this->platosSeleccionados[$indice]['plato_id']);

        if (!$plato) {
            return;
        }

        // Cambiar el estado es_llevar
        $this->platosSeleccionados[$indice]['es_llevar'] = !$this->platosSeleccionados[$indice]['es_llevar'];

        // Actualizar el precio según el nuevo estado
        if ($this->platosSeleccionados[$indice]['es_llevar'] && $plato->precio_llevar > 0) {
            $this->platosSeleccionados[$indice]['precio_unitario'] = $plato->precio_llevar;
        } else {
            $this->platosSeleccionados[$indice]['precio_unitario'] = $plato->precio;
        }

        // Actualizar subtotal
        $this->platosSeleccionados[$indice]['subtotal'] =
            $this->platosSeleccionados[$indice]['cantidad'] *
            $this->platosSeleccionados[$indice]['precio_unitario'];

        $this->calcularTotal();
    }

    // Método para remover un plato
    public function removerPlato($indice)
    {
        if (!isset($this->platosSeleccionados[$indice])) {
            return;
        }

        unset($this->platosSeleccionados[$indice]);
        // Reindexar el array
        $this->platosSeleccionados = array_values($this->platosSeleccionados);

        $this->calcularTotal();
    }

    // Método para calcular el total de la comanda
    private function calcularTotal()
    {
        $this->totalComanda = 0;

        foreach ($this->platosSeleccionados as $item) {
            $this->totalComanda += $item['subtotal'];
        }
    }

    // Método para confirmar el pedido (mostrar modal de confirmación)
    public function confirmarPedido()
    {
        if (count($this->platosSeleccionados) === 0) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'No hay platos seleccionados'
            ]);
            return;
        }

        $this->showConfirmation = true;
    }

    // Ocultar modal de confirmación
    public function hideConfirmation()
    {
        $this->showConfirmation = false;
    }

    // Método para agregar los platos a la comanda
    public function agregarAComanda()
    {
        // Verificar stock antes de confirmar
        $stockSuficiente = true;
        $mensajeError = '';

        foreach ($this->platosSeleccionados as $item) {
            $plato = Plato::with(['disponibilidadPlato'])->find($item['plato_id']);

            if (
                $plato && $plato->disponibilidadPlato &&
                ($plato->disponibilidadPlato->disponibilidad != 'Disponible' ||
                    $plato->disponibilidadPlato->cantidad < $item['cantidad'])
            ) {
                $stockSuficiente = false;
                $mensajeError = 'No hay suficiente stock para ' . $plato->nombre;
                break;
            }
        }

        if (!$stockSuficiente) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $mensajeError
            ]);
            $this->showConfirmation = false;
            return;
        }

        // Emitir evento con los platos seleccionados para que el componente padre los reciba
        $this->dispatch('platosAgregados', [
            'platos' => $this->platosSeleccionados,
            'total' => $this->totalComanda
        ]);

        // Limpiar selección y cerrar modales
        $this->platosSeleccionados = [];
        $this->totalComanda = 0;
        $this->showConfirmation = false;
        $this->showModal = false;
    }
}
