<?php

declare(strict_types=1);

namespace IDCT\TelegramSender;

use Exception;

class TelegramSenderException extends Exception
{
    public const INVALID_RESPONSE = -100;
    
    public function __construct(string $message = '', int $code = 0)
    {
        if ($code === self::INVALID_RESPONSE) {
            $message = "Invalid Bot Id or command.";
        }

        parent::__construct($message, $code);
    }
}
