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
        $response = $this->openAIClient->post('https://api.openai.com/v1/images/generations', [
            'json' => [
                'prompt' => str_replace('/generate', '',str_replace('@Art39GPT_bot', '', $messageDTO->message)),
                'n'      => 1,
                'size'   => '1024x1024',
            ],
        ]);

        $this->sendContentToTelegramAction->execute($messageDTO, $response, $this->sendContentToTelegramAction::SEND_MEDIA);
    }
}
