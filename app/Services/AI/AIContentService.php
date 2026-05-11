<?php

namespace App\Services\AI;

use App\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentService
{
    /**
     * All configured AI model providers.
     */
    private array $providers;

    public function __construct()
    {
        $this->providers = config('ai_models.models', []);
    }

    // -----------------------------------------------------------------
    //  Public API (unchanged signatures)
    // -----------------------------------------------------------------

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

    // -----------------------------------------------------------------
    //  Core Generation Engine
    // -----------------------------------------------------------------

    /**
     * Generate content by trying multiple free AI models in order.
     */
    private function generate(string $type, string $city): string
    {
        $system = $this->getSystemPrompt($type);
        $prompt = $this->buildPrompt($type, $city);

        // Iterate through all configured models until one succeeds
        foreach ($this->providers as $modelKey => $config) {
            try {
                $content = $this->callModel($config, $system, $prompt);
                if ($content !== null && $content !== '') {
                    return trim($content);
                }
            } catch (\Exception $e) {
                Log::warning("AI model [{$config['name']}] failed for type [{$type}]", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // If every model failed, use hardcoded fallback
        Log::error("All AI models failed for type [{$type}], city [{$city}]");
        return $this->getFallback($type, $city);
    }

    /**
     * Call a single AI provider and return the generated text (or null).
     */
    private function callModel(array $config, string $system, string $userPrompt): ?string
    {
        return match ($config['type'] ?? '') {
            'gemini' => $this->callGemini($config, $system, $userPrompt),
            'groq'   => $this->callGroq($config, $system, $userPrompt),
            // add more providers here
            default  => throw new \Exception("Unsupported provider type: {$config['type']}"),
        };
    }

    // -----------------------------------------------------------------
    //  Provider Implementations
    // -----------------------------------------------------------------

    private function callGemini(array $config, string $system, string $userPrompt): ?string
    {
        $response = Http::timeout(30)
            ->post($config['endpoint'] . '?key=' . $config['key'], [
                'system_instruction' => [
                    'parts' => ['text' => $system],
                ],
                'contents' => [
                    'parts' => ['text' => $userPrompt],
                ],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        // Log the error for debugging
        Log::warning('Gemini API error', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return null;
    }

    private function callGroq(array $config, string $system, string $userPrompt): ?string
    {
        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $config['key'],
                'Content-Type'  => 'application/json',
            ])
            ->post($config['endpoint'], [
                'model'       => $config['name'],
                'messages'    => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.7,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        Log::warning('Groq API error', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return null;
    }

    // -----------------------------------------------------------------
    //  Prompt Builders (unchanged logic, just refined)
    // -----------------------------------------------------------------

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
        $cityName = ucwords($city);
        return match ($type) {
            'visa'        => "Visa information for {$cityName} is currently unavailable. Please check the official Kenyan e‑visa website.",
            'best_time'   => "We couldn't retrieve the best time to visit {$cityName} right now. Kenya generally has great weather year‑round!",
            'cultural'    => "Cultural insights for {$cityName} are not available at this moment. Kenyans are known for their warmth and hospitality.",
            'educational' => "Historical information for {$cityName} is temporarily unavailable. Kenya has a rich and diverse history.",
            'full_guide'  => "We're unable to generate a full guide for {$cityName} right now. Explore its beautiful landscapes and vibrant culture.",
            default       => "Discover the beauty and rich culture of {$cityName}.",
        };
    }
}