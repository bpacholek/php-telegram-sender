<?php

declare(strict_types=1);
namespace IDCT\TelegramSender\Tests;

use IDCT\TelegramSender\Bot;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class BotTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testGetAuthKey()
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);
        $this->assertEquals($botId . ':' . $botKey, $bot->getAuthKey());
    }

    public function testGetId()
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);
        $this->assertEquals($botId, $bot->getId());
    }

    public function testGetKey()
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);
        $this->assertEquals($botKey, $bot->getKey());
    }

    public function testInvalidBotId()
    {
        $this->expectException(InvalidArgumentException::class);
        $botId = 0;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);
    }

    public function testInvalidBotKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $botId = 123123;
        $botKey = "";
        $bot = new Bot($botId, $botKey);
    }

    public function testIsValid_false()
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);

        $data = json_encode(
              ['ok' => true,
          'result' => [
              'is_bot' => false
          ]]
          );

        $response = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $response->expects($this->any())->willReturn($data);

        $this->assertEquals(false, $bot->isValid());
    }

    public function testIsValid_true()
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new Bot($botId, $botKey);
          
        $data = json_encode(
              ['ok' => true,
          'result' => [
              'is_bot' => true
          ]]
          );

        $response = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $response->expects($this->any())->willReturn($data);

        $this->assertEquals(true, $bot->isValid());
    }
}
