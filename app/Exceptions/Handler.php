<?php

namespace App\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     * @throws \Exception
     */
    public function report(\Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
        // Set default values for the error (array)
        //
        $error = [
            'status' => 500,
            'message' => 'Internal Server Error',
            // 'code'
            // 'details'
            //
        ];

        /**
         * Force to send JSON response regardless of Accept header.
         *
         * @var bool $forceJson
         */
        $forceJson = false;

        // Try to map exception to error (array)
        //
        if ($e instanceof ApiException) {
            /**
             * @var ApiException $e
             */

            $error['status'] = $e->getCode();
            $error['message'] = $e->getMessage();
            //

            if ($e->hasAppCode()) {
                $error['code'] = (string) $e->getAppCode();
            }
            if ($e->hasDetails() && env('APP_DEBUG', false) === true) {
                $error['details'] = $e->getDetails();
            }
            //

            // Since this is an API exception;
            $forceJson = true;
        } else if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            /**
             * @var \Symfony\Component\HttpKernel\Exception\HttpException $e
             */

            $error['status'] = $e->getStatusCode();
            $error['message'] = $e->getMessage();
            //

            //
        } else if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            /**
             * @var \Illuminate\Database\Eloquent\ModelNotFoundException $e
             */

            $error['status'] = 404;
            $error['message'] = 'Not Found';
            //

            if (env('APP_DEBUG', false) === true) {
                $error['details'] = $e->getMessage();
            }
            //
        } else { // TODO else if ($e instanceof Laravel-/LumenException) (e.g. \Illuminate\Database\Eloquent\ModelNotFoundException)
            $error = null;
        }

        // Exception couldn't be mapped, fallback to framework's renderer
        if ($error === null) {
            return parent::render($request, $e);
        }

        return ($request->expectsJson() || $forceJson === true)
            ? response()->json(['error' => $error], $error['status'])
            : view('Errors.' . $error['status'], $error); // NOTE This might throw view not found error
    }
}
