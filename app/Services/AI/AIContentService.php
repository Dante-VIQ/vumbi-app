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

    foreach ($this->providers as $modelKey => $config) {
        try {
            $content = $this->callModel($config, $system, $prompt);
            if ($content !== null && $content !== '') {
                $content = trim($content);
                // Only enforce length if we have content
                if (is_string($content)) {
                    $content = $this->enforceLength($type, $content);
                }
                return $content;
            }
        } catch (\Exception $e) {
            Log::warning("AI model [{$config['name']}] failed", ['error' => $e->getMessage()]);
        }
    }

    Log::error("All AI models failed for type [{$type}], city [{$city}]");
    return $this->getFallback($type, $city);
}

    private function enforceLength(string $type, string $content): string
{

    $maxChars = [
        'short_intro' => 500,
        'cultural'    => 600,
        'educational' => 600,
        'best_time'   => 400,
        'visa'        => 400,
        'full_guide'  => 1500,
    ];

    $limit = $maxChars[$type] ?? 1000;
    if (mb_strlen($content) > $limit) {
        $content = mb_substr($content, 0, $limit) . '...';
    }
    return $content;
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
                'temperature' => 0.4,
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
    $base = match ($type) {
        'short_intro' => 
            "Write a short 2‑3 sentence travel introduction for {$city}. 
            Be exciting but extremely brief. No extra details.",

        'cultural' => 
            "List the key cultural insights, traditions and local etiquette for {$city} 
            Use bullet points (‑). Each bullet must be one short sentence. 
            Maximum 5 bullets. Do not write a paragraph.",

        'educational' => 
            "Give a concise educational overview of {$city}. 
            Use exactly 3‑4 bullet points (‑) covering history and interesting facts. 
            Each bullet one sentence. No narrative.",

        'best_time' => 
            "What is the best time to visit {$city}? 
            Answer in a single short paragraph of max 3 sentences. 
            Include the best months and a practical tip. No bullet points.",

        'visa' => 
            "Summarize visa and entry requirements for tourists to {$city}. 
            Reply with exactly 2 short sentences. No more.",

        'full_guide' => 
            "Write a mini travel guide for {$city}, using this structure:

            ### Intro
            (2‑3 sentences max)

            ### Top Attractions
            - Bullet list of 3‑4 items, each one line

            ### Culture & Etiquette
            - Bullet list, 3 items max

            ### Best Time to Visit
            (1‑2 sentences)

            ### Visa Info
            (1‑2 sentences)

            Keep every section very short. No long paragraphs.",

        default => 
            "Write a one‑paragraph overview of {$city}. Maximum 4 sentences.",
    };

    // Universal formatting rule – already in base prompts, but we can repeat
    $formatting = "Never write long paragraphs. Use bullet points where instructed. Keep language simple and scannable.";

    return "{$base}\n\n{$formatting}";
}
private function getSystemPrompt(string $type): string
{
    $rolePrompt = match ($type) {
        'visa'        => 'You are an immigration expert specialising in  visa requirements.',
        'best_time'   => 'You are a travel advisor focusing on best seasons, weather, and travel tips.',
        'educational' => 'You are a historian covering in an engaging and accurate way.',
        'cultural'    => 'You are a cultural expert on Africa, explaining traditions and etiquette clearly.',
        'short_intro' => 'You are a captivating travel writer who creates enticing, short introductions to cities.',
        'full_guide'  => 'You are a seasoned travel writer producing detailed, practical guides for Kenya.',
        default       => 'You are a professional travel writer and expert.',
    };

    $formattingRule = 
        "Your responses must be scannable and concise. 
        Use bullet points whenever appropriate. 
        Keep paragraphs under 3 sentences. 
        Never produce a wall of text. 
        When asked for a list, use `-` bullets, one sentence each.";

    return $rolePrompt . ' ' . $formattingRule;
}

    private function getFallback(string $type, string $city): string
    {
        $cityName = ucwords($city);
        return match ($type) {
            'visa'        => "Visa information for {$cityName} is currently unavailable. Please check the official e‑visa website.",
            'best_time'   => "We couldn't retrieve the best time to visit {$cityName} right now. Africa generally has great weather year‑round!",
            'cultural'    => "Cultural insights for {$cityName} are not available at this moment. Kenyans are known for their warmth and hospitality.",
            'educational' => "Historical information for {$cityName} is temporarily unavailable. Africa  has a rich and diverse history.",
            'full_guide'  => "We're unable to generate a full guide for {$cityName} right now. Explore its beautiful landscapes and vibrant culture.",
            default       => "Discover the beauty and rich culture of {$cityName}.",
        };
    }
}