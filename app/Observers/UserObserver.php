<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {

        $role = Role::where('name', 'user')->first();
        if ($role) {
            $user->roles()->attach($role);
        }
        if (! $user->wallet) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $user->roles()->detach();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
