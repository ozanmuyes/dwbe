<?php

namespace App\Tokens;

interface HasLifetime
{
    /**
     * Get token's lifetime in ms. The reason is that property is setting
     * here is to provide a discriminate the token lifetimes by their
     * types. Also some classes (which are not implementing this
     * interface) has no lifetime set, thus will NOT expire.
     * @var int $_lifetime
     * @return int
     */
    function getLifetime(): int;

    /**
     * Set token instance's lifetime in ms.
     * @param int $lifetime
     */
    function setLifetime(int $lifetime);
}
