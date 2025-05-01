<?php

namespace App\ChatBot\Actions;

use JsonException;
use App\ChatBot\DTOs\TelegramMessageDTO;
use GuzzleHttp\Exception\GuzzleException;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ChatGPTAction
{
    public function __construct(
        protected GenerateImageAction $generateImageAction,
        protected GenerateTextAction $generateTextAction
    ) {}

    /**
     * @throws GuzzleException
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function execute(TelegramMessageDTO $messageDTO): void
    {
        if (str_contains($messageDTO->message, '/generate')) {
            $this->generateImageAction->execute($messageDTO);
        } else {
            $this->generateTextAction->execute($messageDTO);
        }
    }
}
