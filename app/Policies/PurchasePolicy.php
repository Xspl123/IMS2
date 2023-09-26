<?php

namespace App\Policies;

use App\Models\AdminModel;
use App\Models\Purchase;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AdminModel $adminModel)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminModel $adminModel, Purchase $purchase)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AdminModel $adminModel)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminModel $adminModel, Purchase $purchase)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminModel $adminModel, Purchase $purchase)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminModel $adminModel, Purchase $purchase)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AdminModel  $adminModel
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminModel $adminModel, Purchase $purchase)
    {
        //
    }
}
