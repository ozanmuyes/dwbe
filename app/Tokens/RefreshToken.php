<?php

namespace App\Tokens;

use App\User;

class RefreshToken extends Token
{
    public const TYPE = 'refresh';
    /**
     * @inheritdoc
     */
    protected $type = self::TYPE;

    /**
     * RefreshToken constructor.
     *
     * @param \App\User $user
     * @param array $customClaims
     */
    public function __construct(User $user, $customClaims = [])
    {
        parent::__construct((string) $user->id, $customClaims);

        $this->audience = $this->getIssuer(); // This type of tokens are meant for their issuer
    }
}
