<?php

namespace App\Tokens;

use App\User;

class PasswordSetToken extends Token implements HasLifetime
{
    use DealsWithLifetime;

    public const TYPE = 'password_set';
    /**
     * @inheritdoc
     */
    protected $type = self::TYPE;

    /**
     * PasswordSetToken constructor.
     *
     * @param \App\User $user
     * @param array $customClaims
     * @throws \Exception
     */
    public function __construct(User $user, $customClaims = [])
    {
        $this->setLifetime((int) env('JWT_PWS_LIFE'));

        parent::__construct((string) $user->id, $customClaims);

        $this->allowedAudiences[] = $this->getIssuer();
        $this->audience = $this->getIssuer(); // This type of tokens are meant for their issuer
    }
}
