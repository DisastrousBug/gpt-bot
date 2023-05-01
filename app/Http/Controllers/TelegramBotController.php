<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Objects\Update;
use App\ChatBot\Actions\ChatGPTAction;
use App\ChatBot\Helpers\TelegramApiClient;
use App\ChatBot\Factories\TelegramMessageFactory;

class TelegramBotController extends Controller
{
    public function __construct(protected TelegramApiClient $telegramApiClient)
    {
    }

    public function webhook(Request $request, ChatGPTAction $action)
    {
        // Get the incoming update from Telegram
        $update = new Update(json_decode($request->getContent(), true), $this->telegramApiClient);

        // Check if the message is a text message from the user
        if ($update->getMessage() && $update->getMessage()->getText()) {

            $telegramUpdateDto = TelegramMessageFactory::getDTO();
            // Get user message from Telegram
            $telegramUpdateDto->message = $update->getMessage()->getText();
            $telegramUpdateDto->messageId = $update->message->messageId;
            $telegramUpdateDto->chatId = $update->getMessage()->getChat()->getId();
            $telegramUpdateDto->replyText = $update->message->from->firstName . ",\n\n";
            if (!str_contains($telegramUpdateDto->message, '/info')) {
                return response("Привет, бот позволяет получать текстовый ответ, а также он может генерировать картинки. Чтобы общаться с ним напишите @(Тег бота) и свой текст, если хотите получить сгенерированную картинку, то добавьте к тегу бота команду /generate и через пробел дальше напишите картинку, которую хотите получить.");
            }
            if (!str_contains($telegramUpdateDto->message, '@Art39GPT_bot')) {
                return response('');
            }

            $action->execute($telegramUpdateDto);
        }

        // Return a blank response to Telegram
        return response('');
    }
}
