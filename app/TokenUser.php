<?php

namespace App;

use Illuminate\Auth\GenericUser;

// TODO Add `username` field to JWT and here
/**
 * Class TokenUser
 *
 * @package App
 * @property-read int id
 * @property-read string role
 */
class TokenUser extends GenericUser
{
    // NOTE This class solely for defining (read-only) properties of a GenericUser
}
