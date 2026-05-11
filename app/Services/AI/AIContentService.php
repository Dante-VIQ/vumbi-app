<?php

namespace App\Services\AI;

use App\Models\City;
use Illuminate\Support\Facades\Log;
use GrokPHP\Laravel\Facades\GrokAI;
use GrokPHP\Client\Config\ChatOptions;
use GrokPHP\Client\Enums\Model;
use GrokPHP\Client\Exceptions\GrokException;
use Exception;

class AIContentService
{

public function __construct()
{
    $apiKey = env('GROK_API_KEY');
    if (!$apiKey) {
        Log::warning("Grok API key is not set. AI content generation will be disabled.");
    }
}
    /**
     * Short city introduction
     */
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

    /**
     * Core generation method
     */
    private function generate(string $type, string $city): string
    {
        try {
            $prompt = $this->buildPrompt($type, $city);

            $options = new ChatOptions(
                model: Model::GROK_2   // You can change to GROK_2_MINI for cheaper/faster
            );

            $response = GrokAI::chat(
                messages: [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($type)],
                    ['role' => 'user',   'content' => $prompt]
                ],
                options: $options
            );

            // Handle both array and object responses
            if (is_array($response)) {
                $result = $response['content'] ?? json_encode($response, JSON_UNESCAPED_UNICODE);
            } else {
                $result = $response->content();
            }
            if (is_array($result)) {
                $result = $result['content'] ?? json_encode($result, JSON_UNESCAPED_UNICODE);
            } elseif (is_object($result)) {
                $result = method_exists($result, '__toString') ? (string) $result : json_encode($result, JSON_UNESCAPED_UNICODE);
            }

            return trim((string) $result);

        } catch (GrokException $e) {
            Log::error("Grok AI Error", [
                'type'    => $type,
                'city'    => $city,
                'message' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error("Unexpected AI Error", [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }

        return $this->getFallback($type, $city);
    }

    private function buildPrompt(string $type, string $city): string
    {
        return match ($type) {
            'short_intro' => "Write a short, exciting 2-4 sentence travel introduction for {$city}.",
            'cultural'    => "Provide key cultural insights, traditions, local etiquette for {$city}, Kenya.",
            'educational' => "Give a concise educational overview: history and interesting facts about {$city}, Kenya.",
            'best_time'   => "What is the best time to visit {$city}, Kenya? Include seasons, weather, and tips.",
            'visa'        => "Summarize visa and entry requirements for tourists visiting {$city}, Kenya.",
            'full_guide'  => "Write a compelling travel guide for {$city}, Kenya. Include introduction, best time to visit, top attractions, and practical tips.",
            default       => "Write about {$city}, Kenya."
        };
    }

    private function getSystemPrompt(string $type): string
    {
        return match ($type) {
            'short_intro' => 'You are a professional, engaging travel writer.',
            'cultural'    => 'You are a cultural expert.',
            'educational' => 'You are a historian and travel educator.',
            'best_time'   => 'You are a travel planning expert.',
            'visa'        => 'You are a travel documentation expert.',
            default       => 'You are a helpful travel assistant.'
        };
    }

    private function getFallback(string $type, string $city): string
    {
        return match ($type) {
            'short_intro' => "Discover the beauty and vibrant culture of " . ucwords($city) . ".",
            default       => "This is an amazing destination full of culture and adventure."
        };
    }
}