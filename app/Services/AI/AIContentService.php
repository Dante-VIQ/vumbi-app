<?php

namespace App\Services\AI;

use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class AIContentService
{
    /**
     * CENTRAL AI GENERATOR (core of the service)
     */
    private function generate(
        string $system,
        string $prompt,
        int $maxTokens = 400,
        float $temperature = 0.7
    ): string {

        $cacheKey = 'ai_'.md5($system.$prompt.$maxTokens.$temperature);

        return Cache::remember($cacheKey, now()->addDays(30), function () use (
            $system,
            $prompt,
            $maxTokens,
            $temperature
        ) {
            try {
                Log::info('OpenAI generation started');

                $response = OpenAI::chat()->create([
                    'model' => 'gpt-4.1-mini',
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

                $content = trim($response->choices[0]->message->content ?? '');

                Log::info('OpenAI generation success');

                return $content;

            } catch (\OpenAI\Exceptions\RateLimitException $e) {
                Log::warning('OpenAI rate limited — retrying in 2s');
                sleep(2);

                // retry once
                return $this->generate($system, $prompt, $maxTokens, $temperature);

            } catch (Exception $e) {
                Log::error('OpenAI generation FAILED', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return '';
            }
        });
    }

    /**
     * Short exciting city introduction
     */
    public function describeCity(string $city): string
    {
        $city = trim($city);

        if (empty($city)) {
            return 'A beautiful destination waiting to be explored.';
        }

        $result = $this->generate(
            'You are a professional travel writer.',
            "Write a short exciting 2–3 sentence travel introduction for {$city}, Kenya. Avoid generic phrases.",
            200,
            0.75
        );

        return $result ?: 'Discover the beauty and culture of '.ucwords($city).'.';
    }

    /**
     * Cultural insights
     */
    public function generateCulturalInfo(string $city): array
    {
        return [
            'content' => $this->generate(
                'You are a cultural anthropologist.',
                "Provide cultural insights, traditions, etiquette, food and people of {$city}, Kenya in bullet points.",
                420,
                0.7
            )
        ];
    }

    /**
     * Educational / Historical Information
     */
    public function generateEducationalInfo(string $city): array
    {
        return [
            'content' => $this->generate(
                'You are a historian.',
                "Give a concise history and significance of {$city}, Kenya.",
                380,
                0.65
            )
        ];
    }

    /**
     * Best Time to Visit
     */
    public function generateBestTimeToVisit(string $city): array
    {
        return [
            'content' => $this->generate(
                'You are a travel planner.',
                "Best time to visit {$city}, Kenya. Include seasons, weather and major events.",
                250,
                0.6
            )
        ];
    }

    /**
     * Visa & Entry Requirements (with disclaimer)
     */
    public function generateVisaInfo(string $city): array
    {
        return [
            'content' => $this->generate(
                'You are a travel documentation expert.',
                "Explain visa requirements for visiting {$city}, Kenya. Add a disclaimer that travelers must verify with official embassy websites before traveling.",
                300,
                0.5
            )
        ];
    }

    /**
     * Full travel guide (SEO optimized)
     */
    public function buildGuide(City $city): string
    {
        $result = $this->generate(
            'You are an expert SEO travel writer.',
            "Write a travel guide for {$city->name}, Kenya with headings:
            Introduction
            Best Time to Visit
            Top Attractions
            Travel Tips
            Keep under 450 words.",
            800,
            0.7
        );

        return $result ?: "Welcome to {$city->name}. A destination full of culture and adventure.";
    }
}