<?php

namespace App\Tokens;

use App\User;

class AccessToken extends Token implements HasLifetime
{

    /**
     * Type of the token, can be any string value that has a meaning to the
     * application. This value will be added to the resultant token's
     * 'ttp' custom claim.
     * The type SHOULD be compatible with the 'Security' array in the 'app.php'.
     * @var string $_type
     */
    protected $_type = 'access';

    protected $_audience_allowed = [
        'app://yeppa-front',
        'srv://yeppa-wss',
        //
    ];

    protected $_lifetime = -1;

    /**
     * AccessToken constructor.
     *
     * @param \App\User $user
     * @param string|array $audience
     * @param array $customClaims
     * @throws \Exception
     */
    public function __construct(User $user, $audience, $customClaims = [])
    {
        $this->_subject = (string) $user->id;
        $this->_audience = $this->filterForAllowedAudiences($audience);

        $this->setLifetime((int) env('JWT_ACC_LIFE'));

        parent::__construct($customClaims);

        $this->_builder = $this->_builder
            ->set('rol', $user->role);
    }

    /**
     * Get token's lifetime in ms. The reason is that property is setting
     * here is to provide a discriminate the token lifetimes by their
     * types. Also some classes (which are not implementing this
     * interface) has no lifetime set, thus will NOT expire.
     * @var int $_lifetime
     * @return int
     */
    function getLifetime(): int
    {
        return $this->_lifetime;
    }

    /**
     * Set token instance's lifetime in ms.
     * @param int $lifetime
     * @throws \Exception Throws an exception when given lifetime is wrong
     */
    function setLifetime(int $lifetime)
    {
        // Once set, lifetime of the token can NOT be changed
        if ($this->_lifetime !== -1) {
            // TODO Show error saying use 'change' method

            return;
        }

        if ($lifetime === 0) {
            throw new \Exception('This token supposed to has lifetime but set to 0.');
        }

        $this->_lifetime = $lifetime;

        // Reset the token string cache, so we can calculate
        // again with new values when needed.
        $this->_signed_token_string = '';
    }

    private function filterForAllowedAudiences($audience)
    {
        if (is_string($audience)) {
            $audience = explode(',', $audience);
        }

        if (count($audience) === 0) {
            return [];
        }

        $allowedAudiences = [];

        foreach ($audience as $value) {
            if (in_array($value, $this->_audience_allowed)) {
                $allowedAudiences[] = $value;
            } else {
                // TODO Log warning
            }
        }

        return $allowedAudiences;
    }

    // TODO Implement `renew` method that takes an expired access token and returns the new one
}
