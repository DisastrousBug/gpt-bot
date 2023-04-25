<?php

namespace App\ChatBot\Actions;

use App\ChatBot\DTOs\TelegramMessageDTO;

class ChatGPTAction
{
    public function __construct(
        protected GenerateImageAction $generateImageAction,
        protected GenerateTextAction $generateTextAction
    ) {
    }

    public function execute(TelegramMessageDTO $messageDTO): void
    {
        if (str_contains($messageDTO->message, '/generate')) {
            $this->generateImageAction->execute($messageDTO);
        } else {
            $this->generateTextAction->execute($messageDTO);
        }
    }
}
