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
        // If more city details are needed, you can expand the prompt here
        return $this->generate('full_guide', $city->name);
    }

    /**
     * Core generation method.
     *
     * @param  string $type The type of content to generate
     * @param  string $city The city name
     * @return string The generated content or fallback
     */
    private function generate(string $type, string $city): string
    {
        try {
            $system = $this->getSystemPrompt($type);
            $prompt = $this->buildPrompt($type, $city);

            $response = GrokAI::chat(
                messages: [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $prompt],
                ],
                options: new ChatOptions(
                    // Ensure you use the latest available model constant.
                    // Check your GrokPHP version – it might be Model::GROK_2_LATEST or similar.
                    model: Model::GROK_2_LATEST
                )
            );

            // Normalise the response to a string.
            // Assuming chat() returns an object with a content() method.
            $content = method_exists($response, 'content')
                ? $response->content()
                : (string) $response;

            return trim($content);

        } catch (GrokException $e) {
            Log::error("Grok AI Error", [
                'type'    => $type,
                'city'    => $city,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        } catch (\Throwable $e) {
            // Catch any other unexpected errors
            Log::error("Unexpected Grok Error", [
                'type'    => $type,
                'city'    => $city,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        // If we reach here, an exception occurred
        Log::warning("Returning fallback content for type [{$type}], city [{$city}]");
        return $this->getFallback($type, $city);
    }

    /**
     * Build the user prompt based on content type.
     */
    private function buildPrompt(string $type, string $city): string
    {
        return match ($type) {
            'short_intro' => "Write a short, exciting 2-4 sentence travel introduction for {$city}.",
            'cultural'    => "Provide key cultural insights, traditions, and local etiquette for {$city}, Kenya.",
            'educational' => "Give a concise educational overview: history and interesting facts about {$city}, Kenya.",
            'best_time'   => "What is the best time to visit {$city}, Kenya? Include seasons and tips.",
            'visa'        => "Summarize visa and entry requirements for tourists visiting {$city}, Kenya.",
            'full_guide'  => "Write a compelling travel guide for {$city}, Kenya.",
            default       => "Write about {$city}, Kenya.",
        };
    }

    /**
     * Tailored system prompts for better AI behaviour.
     */
    private function getSystemPrompt(string $type): string
    {
        return match ($type) {
            'visa'        => 'You are an immigration expert specialising in Kenya visa requirements.',
            'best_time'   => 'You are a Kenyan travel advisor focusing on best seasons, weather, and travel tips.',
            'educational' => 'You are a historian covering Kenya in an engaging and accurate way.',
            'cultural'    => 'You are a cultural expert on Kenya, explaining traditions and etiquette clearly.',
            'short_intro' => 'You are a captivating travel writer who creates enticing, short introductions to Kenyan cities.',
            'full_guide'  => 'You are a seasoned travel writer producing detailed, practical guides for Kenya.',
            default       => 'You are a professional travel writer and expert on Kenya.',
        };
    }

    /**
     * Type-specific fallback messages so the user gets relevant information
     * even when the AI is unavailable.
     */
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