<?php

return [
    'api_key' => env('OPENAI_API_KEY'),

    'image_generation' => [
        'models' => [
            'dall-e-3' => [
                'model' => 'dall-e-3',   // самый свежий DALLE-3 (май 2025)
                'quality' => 'hd',         // 'standard' или 'hd'
                'size' => '1024x1024',  // 256 × 256 • 512 × 512 • 1024 × 1024
            ],
        ],
    ],

    'text_completion' => [
        'models' => [
            'gpt-4o' => [
                'model' => env('OPENAI_MODEL', 'gpt-4o-2024-08-06'),
                'temperature' => env('OPENAI_TEMPERATURE', 0.7),
                'max_tokens' => env('OPENAI_MAX_TOKENS', 1500),
            ],
            'gpt-3.5' => [
                'model' => 'gpt-3.5-turbo',
                'temperature' => 0.5,
                'max_tokens' => 2048,
            ],
        ],
    ],
];
