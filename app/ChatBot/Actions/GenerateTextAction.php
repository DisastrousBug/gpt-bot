<?php

namespace App\ChatBot\Actions;

use App\ChatBot\DTOs\TelegramMessageDTO;
use App\ChatBot\Helpers\OpenAIClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;

class GenerateTextAction
{
    public function __construct(
        protected OpenAIClient $openAIClient,
        protected SendContentToTelegramAction $sendContentToTelegramAction
    ) {}

    /**
     * @throws GuzzleException
     * @throws JsonException|TelegramSDKException
     */
    public function execute(TelegramMessageDTO $messageDTO): void
    {
        $response = $this->openAIClient->chatCompletion(
            [
                [
                    'role' => 'user',
                    'content' => str_replace('@Art39GPT_bot', '', $messageDTO->message),
                ],
            ],
            'gpt-4o'  // choose model alias here; could be dynamic later
        );

        $payload = json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);
        $messageDTO->replyText = Arr::get($payload, 'choices.0.message.content', 'Извините, я не смог сгенерировать ответ.');

        $this->sendContentToTelegramAction->execute($messageDTO, $response);
    }
}
