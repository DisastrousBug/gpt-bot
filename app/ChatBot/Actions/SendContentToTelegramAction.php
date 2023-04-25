<?php

namespace App\ChatBot\Actions;

use Illuminate\Support\Str;
use Telegram\Bot\FileUpload\InputFile;
use Psr\Http\Message\ResponseInterface;
use App\ChatBot\DTOs\TelegramMessageDTO;
use App\ChatBot\Helpers\TelegramApiClient;

class SendContentToTelegramAction
{
    public const SEND_TEXT = 'text';

    public const SEND_MEDIA = 'media';

    public function __construct(protected TelegramApiClient $telegramApiClient)
    {
    }

    public function execute(TelegramMessageDTO $messageDTO, ResponseInterface $chatGPTResponse, $sendType = self::SEND_TEXT): void
    {

        if ($sendType === self::SEND_TEXT) {
            $this->telegramApiClient->sendMessage([
                'chat_id'             => $messageDTO->chatId,
                'text'                => $messageDTO->replyText . (((json_decode((string) ($chatGPTResponse?->getBody())))->choices[0])->message->content),
                'reply_to_message_id' => $messageDTO->messageId,
            ]);
        } else {
            $file = InputFile::create(((json_decode((string) ($chatGPTResponse?->getBody())))->data[0])->url, Str::random());

            $this->telegramApiClient->sendPhoto([
                'photo'               => $file,
                'chat_id'             => $messageDTO->chatId,
                'reply_to_message_id' => $messageDTO->messageId,
            ]);
        }

    }
}
