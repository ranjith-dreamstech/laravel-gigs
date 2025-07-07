<?php

namespace Modules\GeneralSetting\Exceptions;

use Exception;

class CurrencyNotFoundException extends Exception
{
    public function __construct(string $message = 'Currency not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
