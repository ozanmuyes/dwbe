<?php

namespace App\Tokens;

use App\User;

class RefreshToken extends Token
{

    /**
     * Type of the token, can be any string value that has a meaning to the
     * application. This value will be added to the resultant token's
     * 'ttp' custom claim.
     * The type SHOULD be compatible with the 'Security' array in the 'app.php'.
     * @var string $_type
     */
    protected $_type = 'refresh';

    /**
     * RefreshToken constructor.
     *
     * @param \App\User $user
     * @param array $customClaims
     */
    public function __construct(User $user, $customClaims = [])
    {
        $this->_subject = (string) $user->id;

        parent::__construct($customClaims);

        $this->_audience = $this->_issuer; // This type of tokens are meant for their issuer
    }
}
