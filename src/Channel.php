<?php

declare(strict_types=1);

namespace IDCT\TelegramSender;

/**
 * Channel information.
 */
abstract class Channel
{
    /**
     * Channel's identifier.
     *
     * @param int
     */
    protected $channelId;

    /**
     * Creates an instance of the channel.
     *
     * @param int $channelId
     * @return $this
     */
    public function __construct(int $channelId)
    {
        $this->channelId = $channelId;
    }

    /**
     * Returns the previously set channel's id.
     *
     * @return int
     */
    public function getChannelId() : int
    {
        return $this->channelId;
    }

    /**
     * Gets the channel's key which may be a modified version of the id depending on the case.
     *
     * @return int
     */
    abstract public function getChannelKey() : int;
}
