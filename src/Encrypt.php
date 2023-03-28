<?php

namespace Nabil;

use Illuminate\Support\Facades\Hash;

trait Encrypt
{
    /**
     * Encrypt data
     */
    protected static function encrypt_data($data)
    {
        return Hash::make($data);
    }
}