<?php

namespace Kiwilan\Audio\Exceptions;

use Exception;

class AudioException extends Exception
{
    public function __construct($message = '', $code = 0, ?Exception $previous = null)
    {
        $message = "`kiwilan/php-audio` error: {$message}";

        parent::__construct($message, $code, $previous);
    }
}
