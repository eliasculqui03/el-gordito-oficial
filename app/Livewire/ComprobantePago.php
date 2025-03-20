<?php

namespace App\Livewire;

use App\Models\Comanda;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class ComprobantePago extends Component
{

    public $modalVisible = false;

    // Método para abrir el modal
    public function abrirModal()
    {
        $this->modalVisible = true;
    }

    // Método para cerrar el modal
    public function cerrarModal()
    {
        $this->modalVisible = false;
    }

    // Método para generar el comprobante
    public function generarComprobante()
    {
        try {
            // Validar que hay una comanda seleccionada
            if (!$this->comandaSeleccionada || !$this->detallesComanda) {
                session()->flash('error', 'No hay comanda seleccionada para generar comprobante');
                return;
            }

            // Validar los campos requeridos
            $this->validate([
                'tipoComprobante' => 'required',
                'metodoPago' => 'required',
            ], [
                'tipoComprobante.required' => 'Debe seleccionar un tipo de comprobante',
                'metodoPago.required' => 'Debe seleccionar un método de pago',
            ]);

            // Actualizar la comanda en la base de datos
            $comanda = Comanda::find($this->comandaSeleccionada->id);
            if ($comanda) {
                $comanda->estado_pago = 'Pagada';
                $comanda->save();

                // Notificar éxito
                session()->flash('success', 'Comprobante generado correctamente');

                // Cerrar el modal
                $this->cerrarModal();

                // Recargar datos de comandas pendientes
                $this->cargarComandasPendientes();
            } else {
                session()->flash('error', 'No se encontró la comanda seleccionada');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar comprobante: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.comprobante-pago');
    }
}
