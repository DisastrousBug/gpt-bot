<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Objects\Update;
use App\ChatBot\Actions\ChatGPTAction;
use App\ChatBot\Factories\TelegramMessageFactory;

class TelegramBotController extends Controller
{
    public function webhook(Request $request, ChatGPTAction $action)
    {
        // Get the incoming update from Telegram
        $update = new Update(json_decode($request->getContent(), true));

        // Check if the message is a text message from the user
        if ($update->getMessage() && $update->getMessage()->getText()) {

            $telegramUpdateDto = TelegramMessageFactory::getDTO();
            // Get user message from Telegram
            $telegramUpdateDto->message = $update->getMessage()->getText();
            $telegramUpdateDto->messageId = $update->message->messageId;
            $telegramUpdateDto->chatId = $update->getMessage()->getChat()->getId();
            $telegramUpdateDto->replyText = $update->message->from->firstName . ",\n\n";

            if (!str_contains($telegramUpdateDto->message, '@Art39GPT_bot')) {
                return response('');
            }

            $action->execute($telegramUpdateDto);
        }

        // Return a blank response to Telegram
        return response('');
    }
}
