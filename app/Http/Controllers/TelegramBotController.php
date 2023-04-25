<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
//use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        // Create an instance of the Telegram Bot API
        $telegram = new Api('6046988830:AAHqVVUOT9hBzRnyVlc4C9JolNhyEYWcxKk');

        // Get the incoming update from Telegram
        $update = new Update(json_decode($request->getContent(), true), $telegram);

        // Check if the message is a text message from the user
        if ($update->getMessage() && $update->getMessage()->getText()) {
            // Get user message from Telegram
            $message = $update->getMessage()->getText();
            $messageId = $update->message->messageId;

            if(!str_contains($message, '@Art39GPT_bot test')) {
                return response('Извините, создатель АПИ делает улучшения, поэтому пока я не работаю');
            }
            // Call the OpenAI API to get response
            $client = new Client(['headers' => [
                'Authorization' => 'Bearer '.'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',
                'Content-Type' => 'application/json',
            ]]);

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'json' => [
//                    'prompt' => 'Conversation:\nUser: '.$message.'\nBot:',
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            "role" => "user",
                            "content" => $message
                        ]
                    ],
                    'max_tokens' => 1500,
                    'temperature' => 0.7,
                ],
            ]);

            foreach ((json_decode((string)($response?->getBody()))) as $choice) {
                $params = [
                    'chat_id' => $update->getMessage()->getChat()->getId(),
                    'text' => $choice->message->content,
                ];

                if (!is_null($messageId)) {
                   $params['reply_to_message_id'] = $messageId;
                   $messageId = null;
                }

                $telegram->sendMessage($params);
            }
        }

        // Return a blank response to Telegram
        return response('');
    }
}
