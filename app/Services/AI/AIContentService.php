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
        try {
            $prompt = $this->buildPrompt($type, $city);

            $response = GrokAI::chat(
                messages: [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($type)],
                    ['role' => 'user', 'content' => $prompt]
                ],
                options: new ChatOptions(
                    model: Model::GROK_2   // ← Changed to GROK_2_LATEST
                )
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
                'type' => $type,
                'city' => $city,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error("Unexpected Grok Error", ['error' => $e->getMessage()]);
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
        return 'You are a professional travel writer and expert on Kenya.';
    }

    private function getFallback(string $type, string $city): string
    {
        return "Discover the beauty and rich culture of " . ucwords($city) . ".";
    }
}