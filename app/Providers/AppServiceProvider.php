<?php

namespace App\Providers;

use App\ChatBot\Helpers\OpenAIClient;
use App\ChatBot\Helpers\TelegramApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenAIClient::class, function () {
            return new OpenAIClient(['headers' => [
                'Authorization' => 'Bearer ' . config('openai.api_key'),
                'Content-Type' => 'application/json',
            ]]);
        });

        $this->app->singleton(TelegramApiClient::class, function () {
            return new TelegramApiClient(config('telegram.bots.mybot.token'));
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
