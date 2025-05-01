<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\ChatBot\Actions\ChatGPTAction;
use App\ChatBot\Factories\TelegramMessageFactory;
use App\ChatBot\Helpers\TelegramApiClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class TelegramBotController extends Controller
{
    public function __construct(protected TelegramApiClient $telegramApiClient) {}

    public function webhook(Request $request, ChatGPTAction $action): ResponseFactory|Response
    {
        Log::info('TG WEBHOOK HIT', ['raw' => $request->getContent()]);

        // Get the incoming update from Telegram
        $update = new Update(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));

        $baseSocialInstance = $update->getMessage();

        // Check if the message is a text message from the user
        if ($baseSocialInstance instanceof Message) {

            $telegramUpdateDto = TelegramMessageFactory::getDTO();
            // Get user message from Telegram
            $telegramUpdateDto->message = $baseSocialInstance->text;
            $telegramUpdateDto->messageId = $baseSocialInstance->messageId;
            $telegramUpdateDto->chatId = $baseSocialInstance->chat->id;
            $telegramUpdateDto->replyText = $baseSocialInstance->from->firstName.",\n\n";
            if (! str_contains($telegramUpdateDto->message, '/info')) {
                return response('Привет, бот позволяет получать текстовый ответ, а также он может генерировать картинки. Чтобы общаться с ним напишите @(Тег бота) и свой текст, если хотите получить сгенерированную картинку, то добавьте к тегу бота команду /generate и через пробел дальше напишите картинку, которую хотите получить.');
            }
            if (! str_contains($telegramUpdateDto->message, '@Art39GPT_bot')) {
                return response('');
            }

            $action->execute($telegramUpdateDto);
        }

        // Return a blank response to Telegram
        return response('');
    }
}
