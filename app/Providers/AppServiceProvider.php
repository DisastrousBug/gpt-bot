<?php

namespace App\Providers;

use Telegram\Bot\Api;
use App\ChatBot\Helpers\OpenAIClient;
use Illuminate\Support\ServiceProvider;
use App\ChatBot\Helpers\TelegramApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIClient::class, function () {
            return new OpenAIClient(['headers' => [
                'Authorization' => 'Bearer ' . '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',
                'Content-Type'  => 'application/json',
            ]]);
        });

        $this->app->singleton(TelegramApiClient::class, function () {
            return new TelegramApiClient('6046988830:AAHqVVUOT9hBzRnyVlc4C9JolNhyEYWcxKk');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
