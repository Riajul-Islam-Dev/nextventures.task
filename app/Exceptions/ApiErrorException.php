<?php

namespace App\Exceptions;

use Stripe\Exception\ApiErrorException;

class CustomApiErrorException extends ApiErrorException
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
