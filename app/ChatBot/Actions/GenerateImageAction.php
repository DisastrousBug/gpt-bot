<?php

namespace App\ChatBot\Actions;

use App\ChatBot\DTOs\TelegramMessageDTO;
use App\ChatBot\Helpers\OpenAIClient;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;

class GenerateImageAction
{
    public function __construct(
        protected OpenAIClient $openAIClient,
        protected SendContentToTelegramAction $sendContentToTelegramAction
    ) {}

    /**
     * @throws GuzzleException
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function execute(TelegramMessageDTO $messageDTO): void
    {
        $response = $this->openAIClient->imageGeneration($messageDTO->message);
        $payload = json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);

        $messageDTO->replyMediaUrl = $payload['data'][0]['url'] ?? null;

        $this->sendContentToTelegramAction->execute($messageDTO, $response, $this->sendContentToTelegramAction::SEND_MEDIA);
    }
}
