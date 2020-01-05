<?php

namespace IDCT\TelegramSender;

/**
 * Representation of a private channel.
 *
 * Modifies the channel key to match requirements for operation with private channels.
 */
class PrivateChannel extends Channel
{
    /**
     * Channel's key: id with applied filters required for specific cases.
     *
     * @return int;
     */
    protected $channelKey;

    /**
     * {@inheritdoc}
     */
    public function getChannelKey() : int
    {
        if ($this->channelKey === null) {
            $this->channelKey = intval('-100' . $this->channelId);
        }
        
        return $this->channelKey;
    }
}
