<?php

declare(strict_types=1);
namespace IDCT\TelegramSender\Tests;

use IDCT\TelegramSender\Bot;
use IDCT\TelegramSender\Channel;
use IDCT\TelegramSender\ParseMode;
use IDCT\TelegramSender\PrivateChannel;
use IDCT\TelegramSender\TelegramSender;
use IDCT\TelegramSender\TelegramSenderException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TelegramSenderTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testSend() : void
    {
        $telegramSenderBroken = new class extends TelegramSender {
            public static function preparePayload(Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null) : array
            {
                return parent::prepareMessagePayload($channel, $message, $parseMode, $disableWebPagePreview, $disableAudioNotification, $threadId);
            }
        };

        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        $originalContents = $telegramSenderBroken::preparePayload($channel, $message);

        $jsonEncoded = $this->getFunctionMock("IDCT\\TelegramSender\\", "json_encode");
        $jsonEncoded->expects($this->once())->willReturn(\json_encode($originalContents));

        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'any' => 'any' ]
        ]));

        TelegramSender::sendMessage($bot, $channel, $message);
    }

    public function testSend_allArgs() : void
    {
        $telegramSenderBroken = new class extends TelegramSender {
            public static function preparePayload(Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null) : array
            {
                return parent::prepareMessagePayload($channel, $message, $parseMode, $disableWebPagePreview, $disableAudioNotification, $threadId);
            }
        };

        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        $originalContents = $telegramSenderBroken::preparePayload($channel, $message, ParseMode::MARKDOWN(), true, true, 5);

        $jsonEncoded = $this->getFunctionMock("IDCT\\TelegramSender\\", "json_encode");
        $jsonEncoded->expects($this->once())->willReturn(\json_encode($originalContents));

        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'any' => 'any' ]
        ]));

        TelegramSender::sendMessage($bot, $channel, $message);
    }

    public function testSend_ok_false() : void
    {
        $this->expectException(TelegramSenderException::class);
        $telegramSenderBroken = new class extends TelegramSender {
            public static function preparePayload(Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null) : array
            {
                return parent::prepareMessagePayload($channel, $message, $parseMode, $disableWebPagePreview, $disableAudioNotification, $threadId);
            }
        };

        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        $originalContents = $telegramSenderBroken::preparePayload($channel, $message);

        $jsonEncoded = $this->getFunctionMock("IDCT\\TelegramSender\\", "json_encode");
        $jsonEncoded->expects($this->once())->willReturn(\json_encode($originalContents));

        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => false,
            'description' => 'any',
            'error_code' => 900
        ]));

        TelegramSender::sendMessage($bot, $channel, $message);
    }

    public function testSendFail_false() : void
    {
        $this->expectException(TelegramSenderException::class);
                  
        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(false);

        TelegramSender::sendMessage($bot, $channel, $message);
    }

    public function testSendFail_falsejson() : void
    {
        $this->expectException(TelegramSenderException::class);
                  
        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn('abc');

        TelegramSender::sendMessage($bot, $channel, $message);
    }

    public function testSendFail_false_nook() : void
    {
        $this->expectException(TelegramSenderException::class);
                  
        $message = "test message";
        $channel = new PrivateChannel(123123123);
        $bot = new Bot(456456, 'AAFKeyoJC6wHqwW85DfUktEMc2x5iz9melE');
        
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'result' => [ 'any' => 'any' ]
        ]));

        TelegramSender::sendMessage($bot, $channel, $message);
    }
    
    public function testRetrieveBotInfo_invalidArgument() : void
    {
        $this->expectException(InvalidArgumentException::class);
        TelegramSender::retrieveBotInfo(0);
    }

    public function testRetrieveBotInfo() : void
    {
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'id' => 123123,
            'is_bot' => true,
            'username' => 'username',
            'first_name' => 'first',
            'last_name' => 'last'
             ]
        ]));

        $response = TelegramSender::retrieveBotInfo(123123);

        $this->assertEquals($response->getId(), 123123);
        $this->assertEquals($response->getUsername(), 'username');
        $this->assertEquals($response->getFirstName(), 'first');
        $this->assertEquals($response->getLastName(), 'last');
    }

    public function testRetrieveBotInfo_justId() : void
    {
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'id' => 123123,
             ]
        ]));

        $response = TelegramSender::retrieveBotInfo(123123);

        $this->assertEquals($response->getId(), 123123);
        $this->assertEquals($response->getUsername(), null);
        $this->assertEquals($response->getFirstName(), null);
        $this->assertEquals($response->getLastName(), null);
    }

    public function testCheckIfBotIsValid_invalidArgument() : void
    {
        $this->expectException(InvalidArgumentException::class);
        TelegramSender::checkIfBotIsValid(0);
    }

    public function testCheckIfBotIsValid_true() : void
    {
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'is_bot' => true,
             ]
        ]));

        $response = TelegramSender::checkIfBotIsValid(123123);

        $this->assertEquals(true, $response);
    }

    public function testCheckIfBotIsValid_false() : void
    {
        $curlExec = $this->getFunctionMock("IDCT\\TelegramSender\\", "curl_exec");
        $curlExec->expects($this->once())->willReturn(\json_encode([
            'ok' => true,
            'result' => [ 'is_bot' => false,
             ]
        ]));

        $response = TelegramSender::checkIfBotIsValid(123123);

        $this->assertEquals(false, $response);
    }
}
