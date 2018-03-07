<?php

namespace App\Tokens;

use App\User;

class AccessToken extends Token implements HasLifetime
{
    use DealsWithLifetime;

    public const TYPE = 'access';
    /**
     * @inheritdoc
     */
    protected $type = self::TYPE;

    /**
     * @inheritdoc
     */
    protected $allowedAudiences = [
        'app://dwfe',
        //
    ];

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
        $this->audience = $this->filterForAllowedAudiences($audience);

        $this->setLifetime((int) env('JWT_ACC_LIFE'));

        $customClaims['rol'] = $user->role; // TODO Test here
        parent::__construct((string) $user->id, $customClaims);

        // Superseded by line 39
//        $this->builder = $this->builder
//            ->set('rol', $user->role);
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
            if (in_array($value, $this->allowedAudiences)) {
                $allowedAudiences[] = $value;
            } else {
                // TODO Log warning
            }
        }

        return $allowedAudiences;
    }

    // TODO Implement `renew` method that takes an expired access token and returns the new one
}
