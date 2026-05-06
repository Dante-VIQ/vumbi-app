<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;

class TravelAIService
{
    public function describeCity(string $city): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Write a short exciting travel guide intro for {$city}."
                ]
            ]
        ]);

        return $response->choices[0]->message->content;
    }
}