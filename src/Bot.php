<?php

declare(strict_types=1);

namespace IDCT\TelegramSender;

use InvalidArgumentException;

/**
 * Class which represents the Telegram Bot.
 */
class Bot
{
    /**
     * Bot's identifier.
     *
     * The part before ":" from BotFather's response.
     * So for example if BotFather gave you:
     * 771158915:AAFKeyoJC6wHeHW85TfUktEMc2x5iz9melE
     *
     * Then 771158915 is the id.
     *
     * @var int
     */
    protected $id;

    /**
     * Bot's key.
     *
     * The part after ":" from BotFather's response.
     * So for example if BotFather gave you:
     * 771158915:AAFKeyoJC6wHeHW85TfUktEMc2x5iz9melE
     *
     * Then AAFKeyoJC6wHeHW85TfUktEMc2x5iz9melE is the key.
     *
     * @var string
     */
    protected $key;

    /**
     * Creates new instance of the bot.
     *
     * @param int $id Bot's identifier (part before ":" from BotFather's response)
     * @param string $key Bot's key (part after ":" from BotFather's response)
     * @return $this
     */
    public function __construct(int $id, string $key)
    {
        if ($id === 0) {
            throw new InvalidArgumentException("Missing or invalid id.");
        }

        if ($key === '') {
            throw new InvalidArgumentException("Missing key.");
        }

        $this->id = $id;
        $this->key = $key;
    }

    /**
     * Returns the authorization key required by Telegram's APIs.
     * Basically it is a concatnation of id, colon and key.
     *
     * @return string
     */
    public function getAuthKey() : string
    {
        return $this->id . ':' . $this->key;
    }

    /**
     * Returns the id set during creation of the object.
     *
     * @var int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Returns the bot's key set during creation of the object.
     *
     * @var string
     */
    public function getKey() : string
    {
        return $this->key;
    }

    /**
     * Verifies if the bot is valid (by its id).
     *
     * @return bool
     */
    public function isValid() : bool
    {
        return TelegramSender::checkIfBotIsValid($this->id);
    }
}
