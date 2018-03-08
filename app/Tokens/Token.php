<?php

namespace App\Tokens;

use Lcobucci\JWT\Builder;

abstract class Token
{
    /**
     * Underlying token builder instance.
     *
     * @var Builder $builder
     */
    private $builder;

    /**
     * Type of the token, can be any string value that has a meaning to the
     * application. This value will be added to the resultant token's
     * 'ttp' custom claim.
     * The type SHOULD be compatible with the 'Security' array in the 'app.php'.
     *
     * @var string $type
     */
    protected $type;

    /**
     * Subject claim's value. This can be anything (as string).
     *
     * @var string $subject
     */
    private $subject;

    /**
     * Issuer claim's value. This will be automatically set on instantiation.
     * Its value can be changed but the default is decided by environment
     * variables.
     *
     * @var string $issuer
     */
    private $issuer;

    /**
     * Audience claim's value. The audience identifiers may change application
     * to application and there can be 0 or more audiences for the token
     * type interested in.
     *
     * @var array|string Audiences as array of strings.
     */
    protected $audience = [];

    // TODO Write PHPDoc
    protected $allowedAudiences = [];

    // TODO If anything changes this HAVE TO be set to empty string ('').
    protected $_signed_token_string = '';

    /**
     * BaseToken constructor.
     *
     * @param string $subject
     * @param array $customClaims
     */
    public function __construct($subject, $customClaims = [])
    {
        $this->subject = $subject;
        // TODO Initialize other base variables (claims)

        $this->builder = (new Builder())
            ->set('ttp', $this->type)
            ->setIssuer($this->issuer)
            ->setSubject($this->subject)
            ->setAudience(implode(',', $this->audience));
            //
        ;

        // Add extras (if any)
        foreach ($customClaims as $key => $value) {
            // TODO Filter registered claim names from `$key`

            $this->builder = $this->builder->set($key, $value);
        }
    }

    /**
     * @return string
     */
    protected function getIssuer(): string
    {
        if ($this->issuer === null) {
            $this->issuer = env('JWT_ISSUER', env('APP_NAME', ''));
        }

        if ($this->issuer === '') {
            // TODO Log warning here - but continue with the empty string
        }

        return $this->issuer;
    }

    /**
     * Add more audiences to the token.
     * @param string|array $audience
     */
    public function addAudience($audience)
    {
        $audience = is_string($audience)
            ? explode(',', $audience)
            : $audience;

        $this->audience = array_unique(array_merge($this->audience, $audience));

        // Reset the token string cache, so we can calculate
        // again with new values when needed.
        $this->_signed_token_string = '';
    }

    public function __toString()
    {
        if ($this->_signed_token_string === '') {
            $now = time();

            $this->_signed_token_string = $this->builder
                ->setId(md5($this->type . $this->issuer . $now . $this->subject))
                ->setIssuedAt($now);

            if ($this instanceof HasLifetime) {
                $this->builder = $this->builder->setExpiration($now + $this->getLifetime());
            } // else do NOT set expiration

            $this->_signed_token_string = $this->builder
                ->sign(new \Lcobucci\JWT\Signer\Hmac\Sha256(), env('JWT_SECRET', env('APP_KEY')))
                ->getToken();
        }

        return (string) $this->_signed_token_string;
    }
}
