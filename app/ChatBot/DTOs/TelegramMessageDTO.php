<?php

namespace App\ChatBot\DTOs;

use App\Common\DTOs\AbstractDTO;

class TelegramMessageDTO extends AbstractDTO
{
    public ?string $message;

    public ?int $messageId;

    public ?string $replyText;

    public ?int $chatId;

    public ?string $replyMediaUrl = null;
}
