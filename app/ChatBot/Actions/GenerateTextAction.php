<?php

namespace App\ChatBot\Actions;

use App\ChatBot\DTOs\TelegramMessageDTO;
use App\ChatBot\Helpers\OpenAIClient;

class GenerateTextAction
{
    public function __construct(
        protected OpenAIClient $openAIClient,
        protected SendContentToTelegramAction $sendContentToTelegramAction
    ) {}

    public function execute(TelegramMessageDTO $messageDTO): void
    {
        $response = $this->openAIClient->post('https://api.openai.com/v1/chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => str_replace('@Art39GPT_bot', '', $messageDTO->message),
                    ],
                ],
                'max_tokens' => 1500,
                'temperature' => 0.7,
            ],
        ]);

        $this->sendContentToTelegramAction->execute($messageDTO, $response);
    }
}
