<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Model
    |--------------------------------------------------------------------------
    */
    'default' => env('AI_DEFAULT_MODEL', 'gemini-flash'),

    /*
    |--------------------------------------------------------------------------
    | Available Models and Their Settings
    |--------------------------------------------------------------------------
    */
    'models' => [
        'gemini-flash' => [
            'name'     => 'gemini-2.0-flash',
            'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
            'key'      => env('GEMINI_API_KEY'),
            'type'     => 'gemini',
        ],

        'groq-llama' => [
            'name'     => 'llama-3.3-70b-versatile',
            'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
            'key'      => env('GROK_API_KEY'),
            'type'     => 'groq',
        ],

        // Add more models here (Mistral, OpenRouter, local Ollama, etc.)
    ],
];