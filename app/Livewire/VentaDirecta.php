<?php

namespace App\Livewire;

use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Comanda;
use App\Models\DisponibilidadPlato;
use App\Models\Mesa;
use App\Models\Plato;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class VentaDirecta extends Component
{

    public $userType;
    public $selectedCajaId;
    public $cajas;
    public $caja;


    public function mount()
    {
        $this->cargarCaja();
        $this->calcularNumeroPedido();
    }


    public function cargarCaja()
    {
        $user = Auth::user();
        $userCajas = $user->cajas;

        // Si el usuario no tiene cajas, se considera administrador
        if ($userCajas->count() == 0) {
            $this->userType = 'admin';
            $this->cajas = Caja::all();
            $this->selectedCajaId = session('selected_caja_id') ?? ($this->cajas->first()->id ?? null);
        } elseif ($userCajas->count() > 1) {
            $this->userType = 'multiple';
            $this->cajas = $userCajas;
            $this->selectedCajaId = session('selected_caja_id') ?? ($this->cajas->first()->id ?? null);
        } elseif ($userCajas->count() == 1) {
            $this->userType = 'single';
            $this->caja = $userCajas->first();
            $this->selectedCajaId = $this->caja->id;
        }
    }

    public function updatedSelectedCajaId($value)
    {
        session(['selected_caja_id' => $value]);

        $this->dispatch('cajaChanged', value: $value);
    }


    public $numeroPedido;

    public function calcularNumeroPedido()
    {
        $totalComandas = Comanda::count();


        $proximoNumero = $totalComandas + 1;

        $this->numeroPedido = str_pad($proximoNumero, 6, '0', STR_PAD_LEFT);
    }





    public function render()
    {

        return view('livewire.venta-directa');
    }

    //Mesa

    public $id_mesa = '';
    public $id_zona = '';
    public $numero_mesa = '';
    public $nombre_zona = '';

    #[On('mesaZonaActualizada')]
    public function mesaZonaActualizacion($mesa, $zona, $numero, $nombre)
    {
        $this->id_mesa = $mesa;
        $this->id_zona = $zona;
        $this->numero_mesa = $numero;
        $this->nombre_zona = $nombre;
    }


    public function limpiarMesaZona()
    {
        // Encuentra el componente hijo por su ID y ejecuta su método
        $this->dispatch('limpiarMesaZona')->to('cliente.mesa-component');
    }


    public $numero_documento_buscar = '';
    public $razon_social = '';
    public $id_cliente = null;
    public $numero_documento = '';
    public $tipo_documentoNombre = '';
    public $tipo_documentoId = '';
    public $tipo_documentoCodigo = '';
    public $tipo_documento = '';
    public $fecha_cliente = '';
    public $direccion_cliente = '';

    public function buscar()
    {

        if (empty($this->numero_documento_buscar)) {
            Notification::make()
                ->title('Error de validación')
                ->body('El número de documento no puede estar vacío.')
                ->danger()
                ->send();
            return;
        }


        if (!preg_match('/^\d{8,}$/', $this->numero_documento_buscar)) {
            Notification::make()
                ->title('Error de validación')
                ->body('El número de documento debe tener al menos 8 dígitos numéricos.')
                ->danger()
                ->send();
            return;
        }

        $cliente = Cliente::where('numero_documento', $this->numero_documento_buscar)->first();

        if ($cliente) {
            $this->razon_social = $cliente->nombre;
            $this->id_cliente = $cliente->id;
            $this->numero_documento = $cliente->numero_documento;
            $this->tipo_documentoNombre = $cliente->tipoDocumento->descripcion_corta;
            $this->tipo_documento = $cliente->tipoDocumento->tipo;
            $this->tipo_documentoCodigo = $cliente->tipo_documento_id;
            $this->fecha_cliente = $cliente->created_at->format('Y/m/d');
            $this->direccion_cliente = $cliente->direccion;



            Notification::make()
                ->title('Cliente encontrado')
                ->success()
                ->send();
        } else {

            $numero_documento_buscar = $this->numero_documento_buscar;
            $this->dispatch('numeroDocumentoBuscar', numero: $numero_documento_buscar);
            $this->razon_social = '';
            $this->id_cliente = null;
            $this->numero_documento = '';
            $this->tipo_documentoNombre = '';
            $this->tipo_documento = '';
            $this->fecha_cliente = '';
            $this->direccion_cliente = '';

            Notification::make()
                ->title('Cliente no encontrado')
                ->danger()
                ->send();
        }
    }

    public function limpiarCliente()
    {
        $this->numero_documento_buscar = '';
        $this->razon_social = '';
        $this->id_cliente = null;
        $this->numero_documento = '';
        $this->tipo_documentoNombre = '';
        $this->tipo_documentoId = '';
        $this->tipo_documentoCodigo = '';
        $this->tipo_documento = '';
        $this->fecha_cliente = '';
        $this->direccion_cliente = '';
    }
}
