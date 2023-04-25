<?php

namespace App\ChatBot\Actions;

use App\ChatBot\Helpers\OpenAIClient;
use App\ChatBot\DTOs\TelegramMessageDTO;

class GenerateImageAction
{
    public function __construct(
        protected OpenAIClient $openAIClient,
        protected SendContentToTelegramAction $sendContentToTelegramAction
    ) {
    }

    public function execute(TelegramMessageDTO $messageDTO): void
    {
        $response = $this->openAIClient->post('https://api.openai.com/v1/chat/completions', [
            'json' => [
                'model'    => 'image-alpha-001',
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => str_replace('@Art39GPT_bot', '', $messageDTO->message),
                    ],
                ],
                'max_tokens'  => 512,
                'temperature' => 0.5,
            ],
        ]);

        $this->sendContentToTelegramAction->execute($messageDTO, $response, $this->sendContentToTelegramAction::SEND_MEDIA);
    }
}
