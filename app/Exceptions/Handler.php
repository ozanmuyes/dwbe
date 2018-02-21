<?php

namespace App\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $httpStatusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // WebDAV; RFC 2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information', // since HTTP/1.1
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status', // WebDAV; RFC 4918
        208 => 'Already Reported', // WebDAV; RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other', // since HTTP/1.1
        304 => 'Not Modified',
        305 => 'Use Proxy', // since HTTP/1.1
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect', // since HTTP/1.1
        308 => 'Permanent Redirect', // approved as experimental RFC
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324
//        419 => 'Authentication Timeout', // not in RFC 2616
//        420 => 'Enhance Your Calm', // Twitter
//        420 => 'Method Failure', // Spring Framework
        422 => 'Unprocessable Entity', // WebDAV; RFC 4918
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
//        424 => 'Method Failure', // WebDAV)
        425 => 'Unordered Collection', // Internet draft
        426 => 'Upgrade Required', // RFC 2817
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
//        444 => 'No Response', // Nginx
//        449 => 'Retry With', // Microsoft
//        450 => 'Blocked by Windows Parental Controls', // Microsoft
//        451 => 'Redirect', // Microsoft
        451 => 'Unavailable For Legal Reasons', // Internet draft
//        494 => 'Request Header Too Large', // Nginx
//        495 => 'Cert Error', // Nginx
//        496 => 'No Cert', // Nginx
//        497 => 'HTTP to HTTPS', // Nginx
//        499 => 'Client Closed Request', // Nginx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC 2295
        507 => 'Insufficient Storage', // WebDAV; RFC 4918
        508 => 'Loop Detected', // WebDAV; RFC 5842
//        509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
        510 => 'Not Extended', // RFC 2774
        511 => 'Network Authentication Required', // RFC 6585
        598 => 'Network read timeout error', // Unknown
        599 => 'Network connect timeout error', // Unknown
    ];

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
            // NOTE Use empty string as default 'message' since post-process
            //      assigns the 'message' considering (HTTP) status code.
            'message' => '',
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

        /**
         * @var bool $isDebug
         */
        $isDebug = env('APP_DEBUG', false);

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
            if ($e->hasDetails() && $isDebug === true) {
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
            //

            if ($isDebug === true) {
                $error['details'] = $e->getMessage();
            }
            //
        } else if ($e instanceof \Illuminate\Database\Eloquent\MassAssignmentException) {
            /**
             * @var \Illuminate\Database\Eloquent\MassAssignmentException $e
             */

            $error['status'] = 400;
            //

            if ($isDebug) {
                $error['details'] = $e->getMessage();
            }
            //
        } else if ($e instanceof \Illuminate\Database\QueryException) {
            /**
             * @var \Illuminate\Database\QueryException $e
             */

            $error['status'] = 400;
            //

            if ($isDebug) {
                $error['details'] = $e->getMessage();
            }
            //
        } else if ($e instanceof \Illuminate\Validation\ValidationException) {
            /**
             * @var \Illuminate\Validation\ValidationException $e
             */

            $error['status'] = 422;
            //

            if ($isDebug) {
                $error['details'] = $e->getMessage();
                $error['additional'] = ((array) $e->response->getData());
            }
            //
        } else { // TODO else if ($e instanceof Laravel-/LumenException) (e.g. \Illuminate\Database\Eloquent\ModelNotFoundException)
            $error = null;
        }

        // Exception couldn't be mapped, fallback to framework's renderer
        if ($error === null) {
            return parent::render($request, $e);
        }

        // Post-process the error array
        //
        if ($error['message'] === '') {
            $error['message'] = $this->httpStatusCodes[$error['status']];
        }

        return ($request->expectsJson() || $forceJson === true)
            ? response()->json(['error' => $error], $error['status'])
            : view('errors.' . $error['status'], $error); // NOTE This might throw view not found error
    }
}
