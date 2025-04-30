<?php

namespace App\ChatBot\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Lightweight wrapper around Guzzle that keeps the surface‑area small
 * and avoids extending the @final GuzzleHttp\Client class.
 */
class OpenAIClient
{
    private Client $client;

    private const string LATEST_COMPLETION_MODEL_VERSION = 'gpt-4o';

    private const string LATEST_IMAGE_MODEL_VERSION = 'gpt-4o';

    /**
     * @param  array<string, mixed>  $options
     */
    public function __construct(array $options = [])
    {
        $this->client = new Client($options + [
            'base_uri' => $options['base_uri'] ?? 'https://api.openai.com',
        ]);
    }

    /**
     * Proxy method for making requests while preserving response type‑hints.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }

    /**
     * Shorthand for sending a GET request.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    public function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * Shorthand for sending a POST request.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    public function post(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * Shorthand for sending a PUT request.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    public function put(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * Shorthand for sending a DELETE request.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws GuzzleException
     */
    public function delete(string $uri, array $options = []): ResponseInterface
    {
        return $this->request('DELETE', $uri, $options);
    }

    /**
     * Magic passthrough for any other Guzzle methods you may need.
     *
     * @param  array<int, mixed>  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }

    /**
     * @param  array<int, array{role:string,content:string}>  $messages
     * @param  string  $modelKey  Key from config('openai.text_completion.models')
     * @param  array<string,mixed>  $overrides  Extra payload keys that should override defaults
     *
     * @throws GuzzleException
     */
    public function chatCompletion(array $messages, string $modelKey = self::LATEST_COMPLETION_MODEL_VERSION, array $overrides = []): ResponseInterface
    {
        /** @var array<string,mixed>|null $modelCfg */
        $modelCfg = config("openai.text_completion.models.$modelKey");

        $payload = array_merge([
            'model' => $modelCfg['model'] ?? $modelKey,
            'messages' => $messages,
            'max_tokens' => $modelCfg['max_tokens'] ?? config('openai.max_tokens'),
            'temperature' => $modelCfg['temperature'] ?? config('openai.temperature'),
        ], $overrides);

        return $this->post('/v1/chat/completions', ['json' => $payload]);
    }

    /**
     * @param string $prompt
     * @param string $modelKey Key from config('openai.image_generation.models')
     * @param array<string,mixed> $overrides Extra payload keys to override defaults
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function imageGeneration(string $prompt, string $modelKey = self::LATEST_IMAGE_MODEL_VERSION, array $overrides = []): ResponseInterface
    {
        $modelCfg = config("openai.image_generation.models.$modelKey");

        $payload = array_merge([
            'model' => $modelCfg['model'] ?? $modelKey,
            'prompt' => $prompt,
            'size' => $modelCfg['size'] ?? '1024x1024',
            'quality' => $modelCfg['quality'] ?? 'hd',
            'n' => 1,
        ], $overrides);

        return $this->request('POST', '/v1/images/generations', ['json' => $payload]);
    }
}
