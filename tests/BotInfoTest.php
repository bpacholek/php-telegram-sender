<?php

declare(strict_types=1);
namespace IDCT\TelegramSender\Tests;

use IDCT\TelegramSender\BotInfo;
use PHPUnit\Framework\TestCase;

final class BotInfoTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testJustIdSet() : void
    {
        $botId = 123123123;
        $botKey = "AAFKdyoJD6wHmHW85TfUktEMc2x5iz9melE";
        $bot = new BotInfo($botId);
        $this->assertEquals($botId, $bot->getId());
        $this->assertEquals(null, $bot->getUsername());
        $this->assertEquals(null, $bot->getFirstName());
        $this->assertEquals(null, $bot->getLastName());
    }

    public function testAllParams() : void
    {
        $botId = 123123123;
        $username = 'abc';
        $firstname = 'def';
        $lastname = 'gef';
        $bot = new BotInfo($botId, $username, $firstname, $lastname);
        $this->assertEquals($botId, $bot->getId());
        $this->assertEquals($username, $bot->getUsername());
        $this->assertEquals($firstname, $bot->getFirstName());
        $this->assertEquals($lastname, $bot->getLastName());
    }

    public function testIsValid_false() : void
    {
        $botId = 123123123;
        $botId = 123123123;
        $bot = new BotInfo($botId);

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

    public function testIsValid_true() : void
    {
        $botId = 123123123;
        $bot = new BotInfo($botId);

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
