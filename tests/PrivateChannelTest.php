<?php

declare(strict_types=1);
namespace IDCT\TelegramSender\Tests;

use IDCT\TelegramSender\PrivateChannel;
use PHPUnit\Framework\TestCase;

final class PrivateChannelTest extends TestCase
{
    public function testGetChannelKey()
    {
        $channelId = 123123123;
        $privateChannel = new PrivateChannel($channelId);
        $this->assertEquals(intval('-100' . $channelId), $privateChannel->getChannelKey());
    }

    public function testGetChannelId()
    {
        $channelId = 123123123;
        $privateChannel = new PrivateChannel($channelId);
        $this->assertEquals($channelId, $privateChannel->getChannelId());
    }
}
