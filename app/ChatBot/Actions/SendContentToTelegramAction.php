<?php

namespace App\ChatBot\Actions;

use Illuminate\Support\Facades\Log;
use App\ChatBot\DTOs\TelegramMessageDTO;
use App\ChatBot\Helpers\TelegramApiClient;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;

class SendContentToTelegramAction
{
    public const string SEND_TEXT = 'text';

    public const string SEND_MEDIA = 'media';

    public function __construct(protected TelegramApiClient $telegramApiClient) {}

    /**
     * @throws TelegramSDKException
     */
    public function execute(TelegramMessageDTO $messageDTO, ResponseInterface $chatGPTResponse, string $sendType = self::SEND_TEXT): void
    {

        if ($sendType === self::SEND_TEXT) {
            $response = $this->telegramApiClient->sendMessage([
                'chat_id' => $messageDTO->chatId,
                'text' => $messageDTO->replyText,
                'reply_to_message_id' => $messageDTO->messageId,
            ]);

            Log::info('TG send', [
                'body'   => $response->getRawResponse(),
            ]);
        } else {
            if (empty($messageDTO->replyMediaUrl)) {
                $this->telegramApiClient->sendMessage([
                    'chat_id' => $messageDTO->chatId,
                    'text' => 'Не могу сгенерировать фото',
                    'reply_to_message_id' => $messageDTO->messageId,
                ]);

                return;
            }

            $file = InputFile::create($messageDTO->replyMediaUrl, Str::random());

            $this->telegramApiClient->sendPhoto([
                'photo' => $file,
                'chat_id' => $messageDTO->chatId,
                'reply_to_message_id' => $messageDTO->messageId,
            ]);
        }

    }
}
