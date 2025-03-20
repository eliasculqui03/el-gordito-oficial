<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\ComandaExistencia;
use App\Models\ComandaPlato;
use App\Models\Empresa;
use App\Models\Mesa;
use App\Models\Sucursal;
use App\Models\TipoComprobante;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaExistencia;
use App\Models\VentaPlato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CrearVenta extends Component
{
    // Propiedades para búsqueda
    public $searchTerm = '';
    public $totalComandasEncontradas = 0;

    // Propiedades para comanda seleccionada
    public $detallesComanda = null;
    public $productosComanda = [];
    public $subtotal = 0;
    public $igv = 0;
    public $total = 0;

    // Propiedades para comprobante
    public $tipoComprobanteId = '';
    public $metodoPago = '';
    public $observaciones = '';
    public $tipoComprobanteNombre = '';

    // Lista de tipos de comprobante disponibles
    public $tiposComprobante = [];

    // Propiedades para información de empresa
    public $empresaId = null;
    public $nombreEmpresa = '';
    public $nombreSurcursal = '';
    public $direccion = '';
    public $ruc = '';
    public $correoEmpresa = '';

    // Propiedades para permisos y selección
    public $esAdmin = false;
    public $tieneMultiplesCajas = false;
    public $cajaId = null;
    public $sucursalId = null;


    // Colecciones para selectores
    public $cajas = [];
    public $cajaInfo = null;
    public $sucursalInfo = null;
    public $empresaInfo = null;

    protected $rules = [
        'tipoComprobanteId' => 'required|exists:tipo_comprobantes,id',
        'metodoPago' => 'required',
        'cajaId' => 'required_if:tieneMultiplesCajas,true',
    ];

    protected $messages = [
        'tipoComprobanteId.required' => 'Debe seleccionar un tipo de comprobante',
        'tipoComprobanteId.exists' => 'El tipo de comprobante seleccionado no existe',
        'metodoPago.required' => 'Debe seleccionar un método de pago',
        'cajaId.required_if' => 'Debe seleccionar una caja',
    ];

    public function mount()
    {
        // Cargar tipos de comprobante
        $this->tiposComprobante = TipoComprobante::where('estado', 1)->get();

        // Inicializar método de pago predeterminado
        $this->metodoPago = 'Efectivo';

        // Verificar permisos y cargar datos iniciales
        $this->verificarPermisos();
    }


    /**
     * Verificar los permisos y cajas asignadas al usuario actual
     */
    private function verificarPermisos()
    {
        $usuarioId = Auth::id();
        $usuario = User::findOrFail($usuarioId);

        // Obtener las cajas asignadas al usuario a través de la tabla pivote
        $cajasUsuario = $usuario->cajas()
            ->where('cajas.estado', true)
            ->with(['sucursal.empresa'])
            ->get();

        if ($cajasUsuario->isEmpty()) {
            // El usuario no tiene cajas asignadas, asumir que es administrador
            $this->configurarComoAdmin();
        } elseif ($cajasUsuario->count() == 1) {
            // El usuario tiene una sola caja, configurar con esa caja
            $this->configurarConCajaUnica($cajasUsuario->first());
        } else {
            // El usuario tiene múltiples cajas, permitir selección
            $this->configurarConMultiplesCajas($cajasUsuario);
        }
    }

    /**
     * Configurar el componente para un administrador
     */
    private function configurarComoAdmin()
    {
        $this->esAdmin = true;
        $this->tieneMultiplesCajas = false;

        // Cargar todas las cajas activas
        $this->cajas = Caja::where('estado', true)
            ->with(['sucursal.empresa'])
            ->get()
            ->map(function ($caja) {
                return [
                    'id' => $caja->id,
                    'nombre' => $caja->nombre,
                    'sucursal_id' => $caja->sucursal_id,
                    'sucursal_nombre' => $caja->sucursal->nombre,
                    'empresa_id' => $caja->sucursal->empresa_id,
                    'empresa_nombre' => $caja->sucursal->empresa->nombre
                ];
            })
            ->toArray();

        // Si hay cajas, preseleccionar la primera
        if (!empty($this->cajas)) {
            $this->seleccionarCaja($this->cajas[0]['id']);
        } else {
            // No hay cajas configuradas
            $this->nombreEmpresa = 'NO HAY CAJAS CONFIGURADAS';
            $this->direccion = '';
            $this->ruc = '';
        }
    }

    /**
     * Configurar el componente para un usuario con una única caja
     */
    private function configurarConCajaUnica($caja)
    {
        $this->esAdmin = false;
        $this->tieneMultiplesCajas = false;

        // Cargar la información de la caja con sus relaciones
        $cajaConRelaciones = Caja::with(['sucursal.empresa'])->findOrFail($caja->id);
        $this->seleccionarCaja($cajaConRelaciones->id);
    }

    /**
     * Configurar el componente para un usuario con múltiples cajas
     */
    private function configurarConMultiplesCajas($cajasUsuario)
    {
        $this->esAdmin = false;
        $this->tieneMultiplesCajas = true;

        // Preparar array de cajas para el selector
        $this->cajas = $cajasUsuario->map(function ($caja) {
            return [
                'id' => $caja->id,
                'nombre' => $caja->nombre,
                'sucursal_id' => $caja->sucursal_id,
                'sucursal_nombre' => $caja->sucursal->nombre,
                'empresa_id' => $caja->sucursal->empresa_id,
                'empresa_nombre' => $caja->sucursal->empresa->nombre
            ];
        })->toArray();
    }

    /**
     * Seleccionar una caja
     */
    public function seleccionarCaja($cajaId)
    {
        $caja = Caja::with(['sucursal.empresa'])->find($cajaId);

        if (!$caja) {
            Notification::make()
                ->title('Error')
                ->body('La caja seleccionada no existe o ha sido desactivada')
                ->danger()
                ->send();
            return;
        }

        $this->cajaId = $caja->id;
        $this->cajaInfo = $caja;

        $sucursal = $caja->sucursal;
        $this->sucursalId = $sucursal->id;
        $this->sucursalInfo = $sucursal;

        $empresa = $sucursal->empresa;
        $this->empresaId = $empresa->id;
        $this->empresaInfo = $empresa;

        // Actualizar la información para la vista
        $this->nombreEmpresa = $empresa->nombre;
        $this->direccion = $sucursal->direccion ?: $empresa->direccion;
        $this->ruc = $empresa->ruc;
        $this->nombreSurcursal = $sucursal->nombre;
        $this->correoEmpresa = $empresa->email;
    }

    /**
     * Actualiza el nombre del tipo de comprobante cuando cambia la selección
     */
    public function updatedTipoComprobanteId($value)
    {
        if (!empty($value)) {
            $tipoComprobante = TipoComprobante::find($value);
            if ($tipoComprobante) {
                $this->tipoComprobanteNombre = $tipoComprobante->descripcion;
            }
        } else {
            $this->tipoComprobanteNombre = '';
        }
    }

    /**
     * Busca comandas pendientes de pago por DNI/RUC del cliente
     */
    public function buscarPorDNIRUC()
    {
        if (empty($this->searchTerm)) {
            Notification::make()
                ->title('Error')
                ->body('Ingrese un número de documento')
                ->danger()
                ->send();
            return;
        }

        // Verificar que haya una caja seleccionada
        if (empty($this->cajaId)) {
            Notification::make()
                ->title('Error')
                ->body('Debe seleccionar una caja antes de buscar clientes')
                ->danger()
                ->send();
            return;
        }

        // Buscar cliente por número de documento
        $cliente = Cliente::where('numero_documento', $this->searchTerm)->first();

        if (!$cliente) {
            Notification::make()
                ->title('Cliente no encontrado')
                ->body('No existe un cliente con este número de documento')
                ->warning()
                ->send();
            return;
        }

        // Buscar comandas pendientes de pago para este cliente
        $comandas = Comanda::where('cliente_id', $cliente->id)
            ->where('estado', 'Completada')
            ->where('estado_pago', 'Pendiente')
            ->orderBy('id', 'desc')
            ->get();

        $this->totalComandasEncontradas = $comandas->count();

        if ($this->totalComandasEncontradas === 0) {
            Notification::make()
                ->title('Sin comandas pendientes')
                ->body('No se encontraron comandas pendientes para este cliente')
                ->info()
                ->send();

            $this->reset(['detallesComanda', 'productosComanda', 'subtotal', 'igv', 'total']);
            return;
        }

        // Obtener la primera comanda (la más reciente)
        try {
            $comanda = $comandas->first();

            // Cargar los datos de la comanda seleccionada
            $comanda = Comanda::with(['cliente', 'mesa', 'mesa.zona'])->findOrFail($comanda->id);

            // Preparar detalles de la comanda para la vista
            $this->detallesComanda = [
                'id' => $comanda->id,
                'cliente' => [
                    'nombre' => $comanda->cliente->nombre . ' ' . $comanda->cliente->apellido,
                    'numero_documento' => $comanda->cliente->numero_documento,
                    'correo' => $comanda->cliente->correo ?? 'No registrado'
                ],
                'mesa' => [
                    'numero' => $comanda->mesa->numero,
                    'zona' => $comanda->mesa->zona->nombre
                ]
            ];

            // Cargar productos de la comanda (platos y existencias)
            $this->cargarProductosComanda($comanda->id);

            // Calcular subtotal, IGV y total
            $this->calcularTotales();

            // Resetear valores del formulario
            $this->reset(['tipoComprobanteId', 'metodoPago', 'observaciones', 'tipoComprobanteNombre']);

            Notification::make()
                ->title('Búsqueda exitosa')
                ->body('Se encontraron ' . $this->totalComandasEncontradas . ' comanda(s) pendiente(s)')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Error al cargar la comanda: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Carga los productos (platos y existencias) de una comanda
     */
    private function cargarProductosComanda($comandaId)
    {
        // Obtener platos de la comanda
        $platos = ComandaPlato::where('comanda_id', $comandaId)
            ->whereIn('estado', ['Listo', 'Entregando', 'Completado'])
            ->with('plato')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'P' . $item->id,
                    'tipo' => 'plato',
                    'nombre' => $item->plato->nombre,
                    'cantidad' => $item->cantidad,
                    'precio' => $item->plato->precio,
                    'subtotal' => $item->subtotal,
                ];
            });

        // Obtener existencias (bebidas, etc.) de la comanda
        $existencias = ComandaExistencia::where('comanda_id', $comandaId)
            ->whereIn('estado', ['Listo', 'Entregando', 'Completado'])
            ->with('existencia')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'E' . $item->id,
                    'tipo' => 'existencia',
                    'nombre' => $item->existencia->nombre,
                    'cantidad' => $item->cantidad,
                    'precio' => $item->existencia->precio_venta,
                    'subtotal' => $item->subtotal,
                ];
            });

        // Combinar platos y existencias
        $this->productosComanda = $platos->concat($existencias)->toArray();
    }

    /**
     * Calcula los totales (subtotal, IGV, total)
     */
    private function calcularTotales()
    {
        // Calcular el subtotal sumando los subtotales de todos los productos
        $this->total = collect($this->productosComanda)->sum('subtotal');

        // Calcular IGV (18% sobre el subtotal)
        $this->igv = $this->total * 0.18;

        // Calcular total
        $this->subtotal = $this->total - $this->igv;
    }

    /**
     * Genera el comprobante de venta
     */
    public function generarVenta()
    {
        // Validar los campos requeridos
        $this->validate();

        if (!$this->detallesComanda) {
            Notification::make()
                ->title('Error')
                ->body('No hay comanda seleccionada')
                ->danger()
                ->send();
            return;
        }

        // Verificar que haya una caja seleccionada
        if (empty($this->cajaId)) {
            Notification::make()
                ->title('Error')
                ->body('Debe seleccionar una caja antes de generar la venta')
                ->danger()
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            // Obtener la comanda
            $comanda = Comanda::findOrFail($this->detallesComanda['id']);

            // Crear el registro de venta
            $venta = Venta::create([
                'tipo_comprobante_id' => $this->tipoComprobanteId,
                'cliente_id' => $comanda->cliente_id,
                'comanda_id' => $comanda->id,
                'metodo_pago' => $this->metodoPago,
                'subtotal' => $this->subtotal,
                'igv' => $this->igv,
                'total' => $this->total,
                'user_id' => Auth::id(), // ID del usuario autenticado
                'observaciones' => $this->observaciones,
            ]);

            // Registrar los platos en la venta
            $platos = ComandaPlato::where('comanda_id', $comanda->id)
                ->whereIn('estado', ['Listo', 'Entregando', 'Completado'])
                ->with('plato')
                ->get();

            foreach ($platos as $comandaPlato) {
                VentaPlato::create([
                    'venta_id' => $venta->id,
                    'plato_id' => $comandaPlato->plato_id,
                    'cantidad' => $comandaPlato->cantidad,
                    'precio_unitario' => $comandaPlato->plato->precio,
                    'subtotal' => $comandaPlato->subtotal,
                ]);
            }

            // Registrar las existencias (bebidas, etc.) en la venta
            $existencias = ComandaExistencia::where('comanda_id', $comanda->id)
                ->whereIn('estado', ['Listo', 'Entregando', 'Completado'])
                ->with('existencia')
                ->get();

            foreach ($existencias as $comandaExistencia) {
                VentaExistencia::create([
                    'venta_id' => $venta->id,
                    'existencia_id' => $comandaExistencia->existencia_id,
                    'cantidad' => $comandaExistencia->cantidad,
                    'precio_unitario' => $comandaExistencia->existencia->precio_venta,
                    'subtotal' => $comandaExistencia->subtotal,
                ]);
            }

            // Actualizar estado de la comanda a pagada
            $comanda->update(['estado_pago' => 'Pagada']);

            // Actualizar estado de la mesa a Libre
            $mesa = Mesa::find($comanda->mesa_id);
            if ($mesa) {
                $mesa->update(['estado' => 'Libre']);
            }

            DB::commit();

            // Emitir evento para actualizar otras partes de la aplicación si es necesario
            $this->dispatch('ventaRegistrada', $venta->id);

            // Mostrar notificación de éxito
            Notification::make()
                ->title('Venta registrada')
                ->body('La venta se ha registrado correctamente')
                ->success()
                ->send();

            // Limpiar el formulario
            $this->reset(['detallesComanda', 'productosComanda', 'subtotal', 'igv', 'total', 'tipoComprobanteId', 'metodoPago', 'observaciones', 'tipoComprobanteNombre']);
        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error al registrar la venta')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.crear-venta');
    }
}
