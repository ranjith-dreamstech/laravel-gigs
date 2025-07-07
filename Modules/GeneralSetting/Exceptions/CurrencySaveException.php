<?php

namespace Modules\GeneralSetting\Exceptions;

use Exception;

class CurrencySaveException extends Exception
{
    protected $message = 'Failed to save currency';
}
