<?php

namespace App;

use Illuminate\Auth\GenericUser;

/**
 * Class TokenUser
 *
 * @package App
 * @property-read int id
 * @property-read string username
 * @property-read string role
 */
class TokenUser extends GenericUser
{
    // NOTE This class solely for defining (read-only) properties of a GenericUser
}
