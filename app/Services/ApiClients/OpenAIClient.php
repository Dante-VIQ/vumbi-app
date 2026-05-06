<?php

namespace App\Services\ApiClients;

use Illuminate\Support\Facades\Http;

class OpenAIClient
{
    public function generate(string $prompt): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ]);

        return $response->successful()
            ? $response['choices'][0]['message']['content']
            : '';
    }
}