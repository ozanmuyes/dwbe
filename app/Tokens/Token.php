<?php

namespace App\Tokens;

use Lcobucci\JWT\Builder;

abstract class Token
{

    /**
     * Underlying token builder instance.
     * @var Builder $_builder
     */
    protected $_builder;

    /**
     * Type of the token, can be any string value that has a meaning to the
     * application. This value will be added to the resultant token's
     * 'ttp' custom claim.
     * The type SHOULD be compatible with the 'Security' array in the 'app.php'.
     * @var string $_type
     */
    protected $_type;

    /**
     * Subject claim's value. This can be anything (as string).
     * @var string $_subject
     */
    protected $_subject;

    /**
     * Issuer claim's value. This will be automatically set on instantiation.
     * Its value can be changed but the default is decided by environment
     * variables.
     * @var string $_issuer
     */
    protected $_issuer;

    /**
     * Audience claim's value. The audience identifiers may change application
     * to application and there can be 0 or more audiences for the token
     * type interested in.
     * @var array|string Audiences as array of strings.
     */
    protected $_audience = [];

    protected $_audience_allowed = [];

    //

    // TODO If anything changes this HAVE TO be set to empty string ('').
    protected $_signed_token_string = '';

    //

    /**
     * BaseToken constructor.
     * @param array $customClaims
     * @throws \Exception Throws an exception if issuer was not set
     */
    public function __construct($customClaims = [])
    {
        $this->_issuer = env('APP_NAME');
        if ($this->_issuer === null) {
            throw new \Exception('Token issuer MUST be set.');
        }

        // TODO Initialize other base variables (claims)

        $this->_builder = (new Builder())
            ->set('ttp', $this->_type)
            ->setIssuer($this->_issuer)
            ->setSubject($this->_subject)
            ->setAudience(implode(',', $this->_audience));
        //
        ;

        // Add extras (if any)
        foreach ($customClaims as $key => $value) {
            // TODO Filter registered claim names from `$key`

            $this->_builder = $this->_builder->set($key, $value);
        }
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

        $this->_audience = array_unique(array_merge($this->_audience, $audience));

        // Reset the token string cache, so we can calculate
        // again with new values when needed.
        $this->_signed_token_string = '';
    }

    public function __toString()
    {
        if ($this->_signed_token_string === '') {
            $now = time();

            $this->_signed_token_string = $this->_builder
                ->setId(md5($this->_type . $this->_issuer . $now . $this->_subject))
                ->setIssuedAt($now);

            if ($this instanceof HasLifetime) {
                $this->_builder = $this->_builder->setExpiration($now + $this->getLifetime());
            } // else do NOT set expiration

            $this->_signed_token_string = $this->_builder
                ->sign(new \Lcobucci\JWT\Signer\Hmac\Sha256(), env('JWT_SECRET', env('APP_KEY')))
                ->getToken();
        }

        return (string) $this->_signed_token_string;
    }
}
