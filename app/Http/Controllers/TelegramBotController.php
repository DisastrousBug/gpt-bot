<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class TelegramBotController extends Controller
{
    public function webhook(Request $request)
    {
        // Create an instance of the Telegram Bot API
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        // Get the incoming update from Telegram
        $update = new Update(json_decode($request->getContent(), true), $telegram);

        // Check if the message is a text message from the user
        if ($update->getMessage() && $update->getMessage()->getText()) {
            // Get user message from Telegram
            $message = $update->getMessage()->getText();

            // Call the OpenAI API to get response
            $client = new Client(['headers' => [
                'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]]);

            $response = $client->post('https://api.openai.com/v1/engines/davinci-codex/completions', [
                'json' => [
                    'prompt' => 'Conversation:\nUser: '.$message.'\nBot:',
                    'max_tokens' => 50,
                    'temperature' => 0.7,
                    'stop' => ['\n'],
                ],
            ]);

            // Send the response back to the user via Telegram
            $telegram->sendMessage([
                'chat_id' => $update->getMessage()->getChat()->getId(),
                'text' => $response->getBody(),
            ]);
        }

        // Return a blank response to Telegram
        return response('');
    }
}
