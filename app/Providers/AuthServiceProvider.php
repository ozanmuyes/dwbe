<?php

namespace App\Providers;

use Auth;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('JwtSigner', function () {
            return new Sha256();
        });

        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Auth::viaRequest('api', function (Request $request) {
            // Extract token from header
            //
            $matches = [];
            preg_match('/^Bearer (\w.+)$/', $request->header('Authorization'), $matches);
            if (count($matches) < 2) { // No token on (authorization) header
                return null;
            }

            /**
             * @var $tokenString string
             */
            $tokenString = $matches[1];
            $parser = new Parser();
            $token = $parser->parse($tokenString);

            // Verify
            //
            /**
             * @var \Lcobucci\JWT\Signer $signer
             */
            $signer = $this->app->make('JwtSigner');
            if (!$token->verify($signer, env('APP_KEY'))) {
                return null;
            }

            // Validate
            //
            // TODO Check JWT token if valid \
            //      See https://github.com/lcobucci/jwt/blob/3.2/README.md#validating
            $validationData = new ValidationData();

            // FIXME Move these checks somewhere else
            $allowedIssuers = env('JWT_ISS', null);
            if ($allowedIssuers === null) {
                \Log::warning('JWT_ISS was\'t set, in order to correctly validate JWT tokens it MUST be set.');
            }
            $appName = env('APP_NAME', null);
            if ($appName === null) {
                \Log::warning('APP_NAME was\'t set, in order to correctly validate JWT tokens it MUST be set.');
            }

            $validationData->setIssuer($allowedIssuers);
            $validationData->setAudience($appName);

            if (!$token->validate($validationData)) {
                return null;
            }

            // Gather (generic) user
            //
            /**
             * @var $user \Illuminate\Auth\GenericUser
             */
            $user = null;

            if ($token) {
                $user = new GenericUser([
                    'id' => $token->getClaim('sub'),
                    // TODO Add other claims here
                ]);
            }

            return $user;
        });
    }
}
