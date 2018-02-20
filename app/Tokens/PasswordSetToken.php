<?php

namespace App\Tokens;

use App\User;
use Cake\Core\Configure;

class PasswordSetToken extends Token implements HasLifetime
{

    // FIXME Audience is ""

    /**
     * Type of the token, can be any string value that has a meaning to the
     * application. This value will be added to the resultant token's
     * 'ttp' custom claim.
     * The type SHOULD be compatible with the 'Security' array in the 'app.php'.
     * @var string $_type
     */
    protected $_type = 'password_set';

    // For `$_audience_allowed` see constructor

    protected $_lifetime = -1;

    /**
     * PasswordSetToken constructor.
     *
     * @param \App\User $user
     * @param array $customClaims
     */
    public function __construct(User $user, $customClaims = [])
    {
        $this->_subject = (string) $user->id;

        $this->setLifetime((int) Configure::read("Security.token.{$this->_type}.lifetime"));

        parent::__construct($customClaims);

        $this->_audience_allowed[] = $this->_issuer;
        $this->_audience = $this->_issuer;  // This type of tokens are meant for their issuer
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
}
