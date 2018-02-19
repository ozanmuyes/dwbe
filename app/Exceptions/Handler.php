<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     * @throws Exception
     */
    public function report(Exception $e)
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
    public function render($request, Exception $e)
    {
        switch (true) {
            case ($e instanceof ApiException):
                return ($request->expectsJson())
                    ? $this->renderForApi($request, $e)
                    : view('Errors.' . $e->getCode()); // FIXME HTTP status code is 200 - which SHOULD be 503

            // TODO else if ($e instanceof Laravel-/LumenException) (e.g. ModelNotFoundException)
            case ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException): {
                // TODO return ...
            }

            //
        }

        return parent::render($request, $e);
    }

    private function renderForApi(\Illuminate\Http\Request $request, ApiException $e)
    {
        $error = [
            'status' => $e->getCode(),
            'message' => $e->getMessage(),
            //
        ];
        if ($e->hasAppCode()) {
            $error['code'] = (string) $e->getAppCode();
        }
        if ($e->hasDetails() && env('APP_DEBUG', false) === true) {
            $error['details'] = $e->getDetails();
        }

        return response()->json(['error' => $error], $e->getCode());
    }
}
