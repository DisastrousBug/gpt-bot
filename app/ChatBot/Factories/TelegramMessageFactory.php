<?php

namespace App\ChatBot\Factories;

use App\ChatBot\DTOs\TelegramMessageDTO;
use App\Common\Factories\AbstractFactory;

class TelegramMessageFactory extends AbstractFactory
{
    public static function getDTO(): TelegramMessageDTO
    {
        return new TelegramMessageDTO;
    }
}
