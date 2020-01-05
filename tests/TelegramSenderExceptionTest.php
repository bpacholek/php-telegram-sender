<?php

declare(strict_types=1);
namespace IDCT\TelegramSender\Tests;

use IDCT\TelegramSender\TelegramSenderException;
use PHPUnit\Framework\TestCase;

final class TelegramSenderExceptionTest extends TestCase
{
    public function testConstructor()
    {
        $ex = new TelegramSenderException(null, TelegramSenderException::INVALID_RESPONSE);
        $this->assertEquals($ex->getMessage(), 'Invalid Bot Id or command.');

        $ex = new TelegramSenderException('test', 123);
        $this->assertEquals($ex->getMessage(), 'test');
        $this->assertEquals($ex->getCode(), 123);
    }
}
