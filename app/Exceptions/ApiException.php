<?php

namespace App\Exceptions;

use Throwable;

abstract class ApiException extends \Exception
{
    /**
     * @var int $appCode
     */
    protected $appCode = null;

    /**
     * @var string $details
     */
    protected $details = '';

    //

    public function __construct(
        int $appCode = 0,
        string $details = '',
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        // Try to use class' variables if corresponding parameter wasn't set
        //
        if ($message === '' && $this->message !== '') {
            $message = $this->message;
        }

        if ($code === 0) {
            if ($this->code !== '') {
                $code = $this->code;
            } else {
                $code = 500;
            }
        }


        if ($appCode > 0) {
            $this->appCode = $appCode;
        }

        if ($details !== '') {
            $this->details = $details;
        }

        //

        parent::__construct($message, $code, $previous);
    }

    public function hasAppCode()
    {
        return ($this->appCode !== null && $this->appCode !== 0);
    }

    public function hasDetails()
    {
        return ($this->details !== '');
    }

    /**
     * @return int
     */
    public function getAppCode(): int
    {
        return $this->appCode;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    //
}
