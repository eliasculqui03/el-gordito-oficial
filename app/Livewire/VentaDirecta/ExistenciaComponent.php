<?php

namespace App\Livewire\VentaDirecta;

use App\Models\CategoriaExistencia;
use App\Models\Existencia;
use App\Models\TipoExistencia;
use Filament\Notifications\Notification;
use Livewire\Component;


class ExistenciaComponent extends Component
{
    // Propiedades para controlar la interfaz
    public $showModal = false;
    public $selectedTipoExistencia = '';
    public $selectedCategoriaExistencia = '';
    public $search = '';

    // Oyentes para eventos
    protected $listeners = [
        'openExistenciasModal' => 'openModal',
        'refreshExistencias' => '$refresh'
    ];

    public function mount()
    {
        // Seleccionar el primer tipo de existencia por defecto
        $primerTipo = TipoExistencia::where('estado', true)->first();
        if ($primerTipo) {
            $this->selectedTipoExistencia = $primerTipo->id;
            // No seleccionamos ninguna categoría por defecto
            $this->selectedCategoriaExistencia = '';
        }
    }

    public function render()
    {
        $tipos_existencia = TipoExistencia::where('estado', true)->get();

        $categorias_existencia = collect();
        if ($this->selectedTipoExistencia) {
            $categorias_existencia = CategoriaExistencia::where('tipo_existencia_id', $this->selectedTipoExistencia)
                ->where('estado', true)
                ->get();
        }

        $existencias = $this->getExistencias();

        return view('livewire.venta-directa.existencia-component', [
            'tipos_existencia' => $tipos_existencia,
            'categorias_existencia' => $categorias_existencia,
            'existencias' => $existencias
        ]);
    }

    /**
     * Obtiene las existencias según los filtros aplicados
     */
    public function getExistencias()
    {
        // Crear la consulta base
        $query = Existencia::query()->where('estado', true);

        // Aplicar filtro por tipo de existencia
        if ($this->selectedTipoExistencia) {
            $query->where('tipo_existencia_id', $this->selectedTipoExistencia);
        }

        // Aplicar filtro por categoría de existencia
        if ($this->selectedCategoriaExistencia) {
            $query->where('categoria_existencia_id', $this->selectedCategoriaExistencia);
        }

        // Aplicar filtro de búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            });
        }

        // Incluir relaciones necesarias
        $query->with(['unidadMedida', 'inventario' => function ($query) {
            // Por defecto, tomar el primer almacén
            $query->orderBy('almacen_id', 'asc');
        }, 'tipoExistencia', 'categoriaExistencia']);

        // Obtener resultados sin paginación
        return $query->get();
    }

    /**
     * Selecciona un tipo de existencia y resetea la categoría
     */
    public function selectTipo($tipoId)
    {
        $this->selectedTipoExistencia = $tipoId;
        // Al cambiar el tipo, no seleccionamos ninguna categoría específica
        $this->selectedCategoriaExistencia = '';
    }

    /**
     * Selecciona una categoría de existencia
     */
    public function selectCategoria($categoriaId)
    {
        $this->selectedCategoriaExistencia = $categoriaId;
    }

    /**
     * Abre el modal de selección de existencias
     */
    public function openModal()
    {
        // Al abrir el modal, verificamos si ya tenemos un tipo seleccionado
        if (!$this->selectedTipoExistencia) {
            $primerTipo = TipoExistencia::where('estado', true)->first();
            if ($primerTipo) {
                $this->selectedTipoExistencia = $primerTipo->id;
            }
        }

        // Mantenemos categoría vacía
        $this->selectedCategoriaExistencia = '';
        $this->search = '';
        $this->showModal = true;
    }

    /**
     * Cierra el modal de selección de existencias
     */
    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Selecciona una existencia y la pasa al componente para agregarla
     */
    public function selectExistencia($id)
    {
        $existencia = Existencia::with(['unidadMedida', 'inventario', 'tipoExistencia', 'categoriaExistencia'])
            ->find($id);

        if (!$existencia) {
            return;
        }

        // Verificar disponibilidad de stock
        $stock = $existencia->inventario ? $existencia->inventario->stock : 0;

        if ($stock <= 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => "No hay stock disponible para {$existencia->nombre}"
            ]);
            return;
        }

        // Agregar directamente la existencia como "normal" (no helado)
        $this->agregarExistencia($id, false);
    }

    /**
     * Agrega una existencia al componente padre
     */
    public function agregarExistencia($id, $esHelado = false)
    {
        $existencia = Existencia::with(['unidadMedida', 'inventario', 'tipoExistencia', 'categoriaExistencia'])
            ->find($id);

        if (!$existencia) {
            return;
        }

        // Verificar disponibilidad de stock
        $stock = $existencia->inventario ? $existencia->inventario->stock : 0;

        if ($stock <= 0) {
            // Usar notificación de Filament para error
            Notification::make()
                ->title('Sin stock')
                ->body("No hay stock disponible para {$existencia->nombre}")
                ->danger()
                ->send();
            return;
        }

        // Obtener el ID del tipo de existencia "Productos"
        $tipoProductosId = TipoExistencia::where('nombre', 'like', '%producto%')->first()?->id;

        // Verificar si es un producto
        $esProducto = $existencia->tipo_existencia_id == $tipoProductosId;

        // Solo permitir la opción de helado para productos, los insumos siempre son "normal"
        if (!$esProducto) {
            $esHelado = false;
        }

        // Crear el objeto existencia para enviar al componente padre
        $existenciaData = [
            'id' => $existencia->id,
            'nombre' => $existencia->nombre,
            'categoria' => $existencia->categoriaExistencia->nombre,
            'unidad' => $existencia->unidadMedida->nombre,
            'precio_unitario' => $existencia->precio_venta,
            'cantidad' => 1,
            'subtotal' => $existencia->precio_venta * 1,
            'es_helado' => $esHelado,
            'es_producto' => $esProducto,
            'stock_disponible' => $stock
        ];

        // Emitir evento con la existencia al componente padre
        $this->dispatch('existenciaAgregada', existencia: $existenciaData);

        // Texto para notificación
        $tipoTexto = $esProducto ? ($esHelado ? "(Helado)" : "(Normal)") : "";

        // Usar notificación de Filament para éxito
        Notification::make()
            ->title('Existencia agregada')
            ->body("Existencia {$existencia->nombre} {$tipoTexto} agregada")
            ->success()
            ->send();
    }
}
