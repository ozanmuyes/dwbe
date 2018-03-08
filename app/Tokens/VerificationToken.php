<?php

namespace App\Tokens;

class VerificationToken extends Token implements HasLifetime
{
    use DealsWithLifetime;

    public const TYPE = 'verification';
    /**
     * @inheritdoc
     */
    protected $type = self::TYPE;

    /**
     * VerificationToken constructor.
     *
     * Here we are expecting user's email address since the new user wasn't saved
     * in the database yet.
     *
     * @param string $email The email address of the user about to be registered.
     * @param array $customClaims
     */
    public function __construct(string $email, $customClaims = [])
    {
        $this->setLifetime((int) env('JWT_VRF_LIFE'));

        // TODO Consider 'for' claim
        $customClaims['aud'] = $this->getIssuer();
        parent::__construct($email, $customClaims);

        $this->allowedAudiences[] = $this->getIssuer();
        $this->audience = $this->getIssuer(); // This type of tokens are meant for their issuer
    }
}
