<?php

namespace IDCT\TelegramSender;

/**
 * Class used to provide information about a bot returned by Telegram's API.
 */
class BotInfo
{
    /**
     * Bot's identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * Optional username given to the bot during creation.
     *
     * @var string
     */
    protected $username;

    /**
     * Optional first name given to the bot during creation.
     *
     * @var string
     */
    protected $firstName;

    /**
     * Optional last name given to the bot during creation.
     *
     * @var string
     */
    protected $lastName;

    /**
     * Creates the instance of a bot's descriptor.
     *
     * @param int $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(int $id, string $username = null, string $firstName = null, string $lastName = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Returns bot's id.
     *
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Returns the optional username name given to the bot during creation.
     *
     * @return null|string
     */
    public function getUsername() : ?string
    {
        return $this->username;
    }

    /**
     * Returns the optional first name given to the bot during creation.
     *
     * @return null|string
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }

    /**
     * Returns the optional last name given to the bot during creation.
     *
     * @return null|string
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
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
