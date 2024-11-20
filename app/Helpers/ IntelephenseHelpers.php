<?php

namespace Illuminate\Contracts\Auth;

interface Authenticatable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @param  array|string  $permissions
     * @return bool
     */
    public function can($permissions);

    /**
     * Get the number of settings.
     *
     * @return int
     */
    public function numberOfSettings();
}
