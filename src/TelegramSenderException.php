<?php

namespace IDCT\TelegramSender;

use Exception;

class TelegramSenderException extends Exception
{
    public const INVALID_RESPONSE = -100;
    
    public function __construct($message = null, $code = 0)
    {
        if ($code === self::INVALID_RESPONSE) {
            $message = "Invalid Bot Id or command.";
        }

        parent::__construct($message, $code);
    }
}
