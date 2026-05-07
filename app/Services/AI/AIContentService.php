<?php

namespace App\Services\AI;

use App\Models\City;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class AIContentService
{
    /**
     * Short city introduction (used in search)
     */
    public function describeCity(string $city): string
    {
        $city = trim($city);
        if (empty($city)) {
            return "A beautiful destination waiting to be explored.";
        }

        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.75,
                'max_tokens'  => 300,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a professional, engaging travel writer.'
                    ],
                    [
                        'role'    => 'user',
                        'content' => "Write a short, exciting 2-4 sentence travel introduction for {$city}."
                    ]
                ]
            ]);

            return trim($response->choices[0]->message->content ?? '');

        } catch (Exception $e) {
            Log::warning("AI describeCity failed", ['city' => $city, 'error' => $e->getMessage()]);
            return "Discover the charm and beauty of " . ucwords($city) . ".";
        }
    }

    /**
     * Full travel guide (used by Build Job)
     */
    public function buildGuide(City $city): string
    {
        try {
            $prompt = $this->buildFullGuidePrompt($city);

            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.7,
                'max_tokens'  => 800,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are an expert travel content writer.'],
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            return trim($response->choices[0]->message->content ?? '');

        } catch (Exception $e) {
            Log::error("AI buildGuide failed", [
                'city' => $city->name,
                'error' => $e->getMessage()
            ]);

            return "Welcome to {$city->name}. This destination offers rich culture, beautiful landscapes, and unforgettable experiences.";
        }
    }

    private function buildFullGuidePrompt(City $city): string
    {
        return <<<PROMPT
Write a compelling travel guide for {$city->name}, Kenya.

Include:
- Captivating introduction
- Best time to visit
- Top attractions and experiences
- Practical travel tips

Tone: Exciting, informative, and SEO-friendly. Maximum 450 words.
PROMPT;
    }
}