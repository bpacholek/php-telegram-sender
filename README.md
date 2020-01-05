PHP Telegram Sender
===================

Simple PHP library which simplifies the task of sending bot messages using Telegram IM.

## Installation
The best way to install the library in your project is by using **Composer**:
```bash
composer require idct/php-telegram-sender
```
of course you can still manually include all the required files in your project using `using` statements yet **Composer** and autoloading is more than suggested.

## Usage
Actions are handled via static methods as it is meant to be as simple as possible and at the moment there is no real benfit from instances of the main class.

To use it first add somewhere the `use` statement:
```php
use IDCT\TelegramSender\TelegramSender;
```

Then you have few methods available:

* to check if a bot is valid by its bot id:
```php
TelegramSender::checkIfBotIsValid(123123123);
```
where 123123123 is bot's id.

Returns `true` or `false` depending on the fact if bot id is valid.

* you can retrieve basic bot's information using:
```php
TelegramSender::retrieveBotInfo(123123123);
```
where 123123123 is bot's id.

Returns an instance of `BotInfo`.

* the main method of the library is `sendMessage`:
```php
TelegramSender::sendMessage(Bot $bot, Channel $channel, string $message, ParseMode $parseMode = null, bool $disableWebPagePreview = false, bool $disableAudioNotification = false, int $threadId = null);
```

Messages support __HTML__ and __Markdown__ depending on the `$parseMode` arguemnt's value.

`$channel` must be an instance of `PublicChannel` or `PrivateChannel`.

Example:
```php
TelegramSender::sendMessage(
    new Bot(123123123, 'AAFKeyoJC6wHmHW85TfUktEMc2x5iz9melE'),
    new PrivateChannel(90808012),
    'my message'
);
```

Values for the `Bot` and `Channel` constructors can be obtained from the Telegram app: check descriptions below.

Method returns an array of elements listed here: https://core.telegram.org/bots/api#message

__TODO__: Message entity in the library.

## How to create a new bot?

1. Contact `BotFather` user on Telegram

![BotFather](https://idct.pl/shared/telegram-sender/bot1.png)

2. Type `/newbot` to the `BotFather` and answer all the asked questions.

![New bot](https://idct.pl/shared/telegram-sender/bot2.png)

3. The part which looks like __635092640:AAGfG72lFg0L_Uf8Cfhb5wBi4UFTm7R2lDY__ are your bot's id and key.
The part before `:` is the id and the part after is the key. So for the example above it would be:

`id: 635092640`
`key: AAGfG72lFg0L_Uf8Cfhb5wBi4UFTm7R2lDY`

so you would create an instance of the bot as follows:

```php
$bot = new Bot(635092640, 'AAGfG72lFg0L_Uf8Cfhb5wBi4UFTm7R2lDY');
```

## Where to get the channel's id from?

1. Create a channel using your Telegram client (`New channel` option in the hamburger menu on the left usually).

2. Add your bot as the admin of the channel.

3. Open `https://web.telegram.org/` and sign in using your account. 

4. Click on the desired channel. The url in your browser will change.

5. If the url changes to: `https://web.telegram.org/#/im?p=c1282543751_17534450962567305630` then `1282543751` is your channel's id. So basically the part between `c` and `_` after `?` (in the query string). 

6. Use it to create `PrivateChannel` or `PublicChannel` instance depending on the visibility of your channel.

...I know it is quite complicated, but at the moment Telegram's API does not expose any method to get bot's channels.

## Contribution

In order to contribute open a Pull Request or an Issue. At the moment there are two major points where contribution is more than welcome: support for additional methods of Telegram's API and better unit tests. Handling of the responses: like creating an entity class for the response would be very useful too.
