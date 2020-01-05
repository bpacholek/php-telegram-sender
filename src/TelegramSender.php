<?php

namespace IDCT\TelegramSender;

use InvalidArgumentException;

/**
 * Simple Telegram Sender.
 *
 * Provides a simple and static way of sending a message to Telegram IM by using a bot.
 */
class TelegramSender
{
    private const ENDPOINT = "https://api.telegram.org/bot";

    /**
     * Sends the message.
     *
     * $parseMode parameter defines if text should be parsed as HTML or Markdown contents.
     *
     * disableWebPagePreview: if set to true then Telegram will not try to generate previews out of links in the contents.
     * disableAudioNotification: if set to true then Telegram will not use audio notification for the message.
     *
     * thread id: if provided then Telegram will try to match the message as a response to a one identified by the parameter.
     *
     * @param Bot $bot instance of Bot class with id and key set.
     * @param Channel $channel
     * @param string $message
     * @param ParseMode $parseMode string parsing mode, defaults to HTML (if set to null).
     * @param bool disableWebPagePreview If set to true then Telegram will not try to generate previews out of links in the contents.
     * @param bool disableAudioNotification If set to true then Telegram will not use audio notification for the message.
     * @param int threadId
     * @return array
     * @throws TelegramSenderException
     */
    public static function sendMessage(Bot $bot, Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null)
    {
        $url = self::ENDPOINT . $bot->getAuthKey() . '/sendMessage';
        $payload = static::prepareMessagePayload($channel, $message, $parseMode, $disableWebPagePreview, $disableAudioNotification, $threadId);

        return static::call($url, $payload)['result'];
    }

    /**
     * Returns basic bot's information: first, last, username (if set).
     *
     * @param int $botId
     * @throws InvalidArgumentException
     * @throws TelegramSenderException
     * @return BotInfo
     */
    public static function retrieveBotInfo(int $botId)
    {
        if (!is_int($botId) || $botId === 0) {
            throw new InvalidArgumentException("Invalid or missing bot id.");
        }
        $url = self::ENDPOINT . $botId . '/getMe';
        $data = static::call($url)['result'];

        return new BotInfo($data['id'], isset($data['username']) ? $data['username'] : null, isset($data['first_name']) ? $data['first_name'] : null, isset($data['last_name']) ? $data['last_name'] : null);
    }

    /**
     * Checks if provided id matches a valid telegram bot.
     *
     * @param int $botId
     * @throws InvalidArgumentException
     * @throws TelegramSenderException
     * @return bool
     */
    public static function checkIfBotIsValid(int $botId) : bool
    {
        if (!is_int($botId) || $botId === 0) {
            throw new InvalidArgumentException("Invalid or missing bot id.");
        }
        $url = self::ENDPOINT . $botId . '/getMe';

        return static::call($url)['result']['is_bot'];
    }

    /**
     * Preparse the actual message, creates a structure required by Telegram's API.
     *
     * $parseMode parameter defines if text should be parsed as HTML or Markdown contents.
     *
     * disableWebPagePreview: if set to true then Telegram will not try to generate previews out of links in the contents.
     * disableAudioNotification: if set to true then Telegram will not use audio notification for the message.
     *
     * thread id: if provided then Telegram will try to match the message as a response to a one identified by the parameter.
     *
     * @param Bot $bot instance of Bot class with id and key set.
     * @param Channel $channel
     * @param string $message
     * @param ParseMode $parseMode string parsing mode, defaults to HTML (if set to null).
     * @param bool disableWebPagePreview If set to true then Telegram will not try to generate previews out of links in the contents.
     * @param bool disableAudioNotification If set to true then Telegram will not use audio notification for the message.
     * @param int threadId
     * @throws TelegramSenderException
     */
    protected static function prepareMessagePayload(Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null) : array
    {
        if ($parseMode === null) {
            $parseMode = ParseMode::HTML();
        }

        $payload = [
            'chat_id' => $channel->getChannelKey(),
            'text' => $message,
            'parse_mode' => $parseMode,
            'disable_web_page_preview' => $disableWebPagePreview,
            'disable_notification' => $disableAudioNotification,
        ];

        if (is_int($threadId)) {
            $payload['reply_to_message_id'] = $threadId;
        }

        return $payload;
    }

    /**
     * Performs the actual Telegram's API call.
     *
     * When $payload is provided sends data as POST.
     *
     * @param string $url
     * @param array $payload
     * @throws TelegramSenderException
     * @return array
     */
    protected static function call(string $url, array $payload = null) : array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($payload) && is_array($payload)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $result = curl_exec($ch);
        
        if ($result === false) {
            $errorText = curl_error($ch);
            $errorNo = curl_errno($ch);
            curl_close($ch);
            throw new TelegramSenderException('Connection error: ' . $errorText, -500 + $errorNo);
        }
        curl_close($ch);
        $response = json_decode($result, true);
        if ($response === false || !isset($response['ok'])) {
            throw new TelegramSenderException($response, TelegramSenderException::INVALID_RESPONSE);
        }

        if ($response['ok'] === false) {
            throw new TelegramSenderException($response['description'], $response['error_code']);
        }

        return $response;
    }
}
