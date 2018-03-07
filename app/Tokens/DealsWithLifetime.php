<?php

namespace App\Tokens;

trait DealsWithLifetime
{
    protected $lifetime = -1;

    /**
     * Get token's lifetime in ms. The reason is that property is setting
     * here is to provide a discriminate the token lifetimes by their
     * types. Also some classes (which are not implementing this
     * interface) has no lifetime set, thus will NOT expire.
     *
     * @var int $lifetime
     * @return int
     */
    function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Set token instance's lifetime in ms.
     *
     * @param int $lifetime
     * @throws \Exception Throws an exception when given lifetime is wrong
     */
    function setLifetime(int $lifetime)
    {
        // Once set, lifetime of the token can NOT be changed
        if ($this->lifetime !== -1) {
            // TODO Show error saying use 'change' method

            return;
        }

        if ($lifetime === 0) {
            throw new \Exception('This token supposed to has lifetime but set to 0.');
        }

        $this->lifetime = $lifetime;

        // Reset the token string cache, so we can calculate
        // again with new values when needed.
        $this->_signed_token_string = '';
    }
}
