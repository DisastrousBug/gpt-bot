<?php

namespace App\ChatBot\DTOs;

use App\Common\DTOs\AbstractDTO;

class TelegramMessageDTO extends AbstractDTO
{
    public ?string $message;

    public ?string $messageId;

    public ?string $replyText;

    public ?string $chatId;
}
