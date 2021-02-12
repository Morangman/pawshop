<?php

declare(strict_types = 1);

namespace App\Observers;

use App\User;

use function array_merge, random_int;

/**
 * Class UserObserver
 *
 * @package App\Observers
 */
class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param \App\User $user
     *
     * @return void
     *
     * @throws \Exception
     */
    public function creating(User $user): void
    {
        $registerCode = random_int(100000, 999999);

        $user->setAttribute('register_code', $registerCode);
    }
}
