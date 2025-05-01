<?php

namespace App\Messengers;

use App\Messengers\Drivers\MessengerDriverInterface;

interface MessengerInterface
{
    public function getMessage(): string;

    public function sendMessage(MessengerDriverInterface $messengerDriver): bool;
}
