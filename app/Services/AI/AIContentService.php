<?php

namespace App\Services\AI;

use App\Models\City;
use Illuminate\Support\Facades\Log;
use GrokPHP\Laravel\Facades\GrokAI;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;

class AIContentService
{
    public function __construct()
    {
        if (empty(env('GROK_API_KEY'))) {
            Log::error("GROK_API_KEY is missing in .env");
        }
    }

    public function describeCity(string $city): string
    {
        return $this->generate('short_intro', $city);
    }

    public function generateCulturalInfo(string $city): array
    {
        return ['content' => $this->generate('cultural', $city)];
    }

    public function generateEducationalInfo(string $city): array
    {
        return ['content' => $this->generate('educational', $city)];
    }

    public function generateBestTimeToVisit(string $city): array
    {
        return ['content' => $this->generate('best_time', $city)];
    }

    public function generateVisaInfo(string $city): array
    {
        return ['content' => $this->generate('visa', $city)];
    }

    public function buildGuide(City $city): string
    {
        return $this->generate('full_guide', $city->name);
    }

    private function generate(string $type, string $city): string
    {
          $models = [Model::GROK_2_LATEST, Model::GROK_2]; // try latest, then stable

    foreach ($models as $model) {
        try {
            $prompt = $this->buildPrompt($type, $city);

            $response = GrokAI::chat(
                messages: [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($type)],
                    ['role' => 'user', 'content' => $prompt]
                ],
                options: new ChatOptions(
                    model: $model     // ← This is the correct one
                )
            );

            return trim($response->content());

        } catch (GrokException $e) {
            Log::error("Grok AI Error", [
                'type' => $type,
                'city' => $city,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error("Grok Error", ['error' => $e->getMessage()]);
        }

    }

        return $this->getFallback($type, $city);
    }

    private function buildPrompt(string $type, string $city): string
    {
        return match ($type) {
            'short_intro' => "Write a short, exciting 2-4 sentence travel introduction for {$city}.",
            'cultural'    => "Provide key cultural insights, traditions, and local etiquette for {$city}, Kenya.",
            'educational' => "Give a concise educational overview: history and interesting facts about {$city}, Kenya.",
            'best_time'   => "What is the best time to visit {$city}, Kenya? Include seasons and tips.",
            'visa'        => "Summarize visa and entry requirements for tourists visiting {$city}, Kenya.",
            'full_guide'  => "Write a compelling travel guide for {$city}, Kenya.",
            default       => "Write about {$city}, Kenya."
        };
    }

    private function getSystemPrompt(string $type): string
    {
        return 'You are a professional, friendly travel writer and Kenya expert.';
    }

    private function getFallback(string $type, string $city): string
    {
        return "Discover the beauty and rich culture of " . ucwords($city) . ".";
    }
}