<?php

namespace IDCT\TelegramSender;

/**
 * Representation of a public channel.
 */
class PublicChannel extends Channel
{
    /**
     * Returns the channel key, for public channels it is equal to channel id.
     *
     * @return int
     */
    public function getChannelKey() : int
    {
        return $this->channelId;
    }
}
