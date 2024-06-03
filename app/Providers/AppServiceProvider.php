<?php

namespace App\Providers;

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
