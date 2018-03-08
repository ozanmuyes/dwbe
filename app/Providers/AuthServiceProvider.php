<?php

namespace App\Providers;

use App\Tokens\Validators\Validator;
use App\TokenUser;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class AuthServiceProvider extends ServiceProvider
{
    private function registerAbilities()
        /** @noinspection PhpUndefinedMethodInspection */
    {
        // Gates for user controller
        //
        Gate::define('user.index', function (TokenUser $user) {
            return ($user->role === 'admin');
        });

        Gate::define('user.view', function (TokenUser $user, $targetUserId) {
            return (
                $user->role === 'admin' ||
                $user->role === 'mod' ||
                $user->id === $targetUserId
            );
        });

        //
    }

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

        $this->registerAbilities();
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        Auth::viaRequest('api', function (Request $request) {
            // Get the (access) token and validate it
            //
            $tokenFromHeader = $request->bearerToken();
            if ($tokenFromHeader === null) {
                return null;
            }

            /** @var \Lcobucci\JWT\Token $token */
            $token = Validator::validate($tokenFromHeader);

            // Check for required claims
            //
            if (
                !$token->hasClaim('sub') ||
                !$token->hasClaim('rol') ||
                //
                false
            ) {
                return null;
            }

            // Create the token user and return
            //
            $user = new TokenUser([
                'id' => (int) $token->getClaim('sub'),
                'role' => $token->getClaim('rol'),
                // TODO Add other claims here - don't forget to update TokenUser read-only properties
            ]);

            return $user;
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function bootOld()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Auth::viaRequest('api', function (Request $request) {
            // Get and parse the (access) token
            //
            $tokenFromHeader = $request->bearerToken();
            if ($tokenFromHeader === null) {
                return null;
            }

            /** @var \Lcobucci\JWT\Token $token */
            $token = null;
            try {
                $token = (new Parser())->parse($tokenFromHeader);
            } catch (\Exception $e) {
                return null;
            }

            // Verify the (access) token
            //
            /** @var \Lcobucci\JWT\Signer $signer */
            $signer = $this->app->make('JwtSigner');
            if (!$token->verify($signer, env('JWT_SECRET', env('APP_KEY')))) {
                return null;
            }

            // FIXME Move validation logic to somewhere else (i.e. App\Tokens\Validator)
            // TODO Also check if token type ('ttp' claim) (e.g. 'access', 'refresh' etc.)
            // Validate the (access) token
            //
//            $validationData = new ValidationData();
//
//            // FIXME Move these checks somewhere else
//            $allowedIssuers = env('JWT_ISS', null);
//            if ($allowedIssuers === null) {
//                \Log::warning('JWT_ISS was\'t set, in order to correctly validate JWT tokens it MUST be set.');
//            }
//            $appName = env('APP_NAME', null);
//            if ($appName === null) {
//                \Log::warning('APP_NAME was\'t set, in order to correctly validate JWT tokens it MUST be set.');
//            }
//
//            $validationData->setIssuer($allowedIssuers);
//            $validationData->setAudience($appName);
//
//            if (!$token->validate($validationData)) {
//                return null;
//            }

            // TODO Activate this check
//            if ($token->isExpired()) {
//                return null;
//            }

            // Create (generic) user if required claims exists
            //
            if (
                !$token->hasClaim('sub') ||
                !$token->hasClaim('rol') ||
                //
                false
            ) {
                return null;
            }

            $user = new TokenUser([
                'id' => (int) $token->getClaim('sub'),
                'role' => $token->getClaim('rol'),
                // TODO Add other claims here - don't forget to update TokenUser read-only properties
            ]);

            return $user;
        });
    }
}
