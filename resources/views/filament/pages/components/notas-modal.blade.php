<x-filament::modal.heading>
    Historial de Notas
</x-filament::modal.heading>
<div class="space-y-4">
    @forelse($notas as $nota)
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="font-medium text-gray-900 dark:text-gray-100">
                {{ $nota->motivo }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $nota->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="mt-2 text-gray-700 dark:text-gray-300">
                {{ $nota->nota }}
            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400">
            No hay notas disponibles
        </div>
    @endforelse
</div>
