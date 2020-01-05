<?php

namespace IDCT\TelegramSender;

use MyCLabs\Enum\Enum;

/**
 * ParseMode enum.
 */
class ParseMode extends Enum
{
    private const HTML = 'HTML';
    private const MARKDOWN = 'Markdown';
}
