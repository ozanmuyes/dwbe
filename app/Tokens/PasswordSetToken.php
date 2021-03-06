<?php

namespace App\Tokens;

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
     * Here we are expecting user's email address since the new user wasn't saved
     * in the database yet.
     *
     * @param string $email The email address of the user about to be registered.
     * @param array $customClaims
     */
    public function __construct(string $email, $customClaims = [])
    {
        $this->setLifetime((int) env('JWT_PWS_LIFE'));

        // TODO Consider 'for' claim
        $customClaims['aud'] = $this->getIssuer();
        parent::__construct($email, $customClaims);

        $this->allowedAudiences[] = $this->getIssuer();
        $this->audience = $this->getIssuer(); // This type of tokens are meant for their issuer
    }
}
