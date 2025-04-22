<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DisponibilidadPlato;
use Illuminate\Auth\Access\HandlesAuthorization;

class DisponibilidadPlatoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_disponibilidad::plato');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('view_disponibilidad::plato');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_disponibilidad::plato');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('update_disponibilidad::plato');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('delete_disponibilidad::plato');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_disponibilidad::plato');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('force_delete_disponibilidad::plato');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_disponibilidad::plato');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('restore_disponibilidad::plato');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_disponibilidad::plato');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, DisponibilidadPlato $disponibilidadPlato): bool
    {
        return $user->can('replicate_disponibilidad::plato');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_disponibilidad::plato');
    }
}
