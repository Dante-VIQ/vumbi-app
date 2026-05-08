<?php

namespace App\Services\AI;

use App\Models\City;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class AIContentService
{
    /**
     * Short exciting city introduction (used in search)
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
                    ['role' => 'system', 'content' => 'You are a professional, engaging travel writer.'],
                    ['role' => 'user', 'content' => "Write a short, exciting 2-4 sentence travel introduction for {$city}."]
                ]
            ]);

            return trim($response->choices[0]->message->content ?? '');
        } catch (Exception $e) {
            Log::warning("AI describeCity failed", ['city' => $city]);
            return "Discover the charm and beauty of " . ucwords($city) . ".";
        }
    }

    /**
     * Cultural insights
     */
    public function generateCulturalInfo(string $city): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.7,
                'max_tokens'  => 420,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a cultural anthropologist and travel expert.'],
                    ['role' => 'user', 'content' => "Provide key cultural insights, traditions, local etiquette, and people for {$city}, Kenya."]
                ]
            ]);

            return ['content' => trim($response->choices[0]->message->content ?? '')];
        } catch (Exception $e) {
            return ['content' => ''];
        }
    }

    /**
     * Educational / Historical Information
     */
    public function generateEducationalInfo(string $city): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.65,
                'max_tokens'  => 380,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a historian and travel educator.'],
                    ['role' => 'user', 'content' => "Give a concise educational overview: history, significance, and interesting facts about {$city}, Kenya."]
                ]
            ]);

            return ['content' => trim($response->choices[0]->message->content ?? '')];
        } catch (Exception $e) {
            return ['content' => ''];
        }
    }

    /**
     * Best Time to Visit
     */
    public function generateBestTimeToVisit(string $city): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.6,
                'max_tokens'  => 250,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a travel planning expert.'],
                    ['role' => 'user', 'content' => "What is the best time to visit {$city}, Kenya? Include seasons, weather, events, and travel tips."]
                ]
            ]);

            return ['content' => trim($response->choices[0]->message->content ?? '')];
        } catch (Exception $e) {
            return ['content' => ''];
        }
    }

    /**
     * Visa & Entry Requirements
     */
    public function generateVisaInfo(string $city): array
    {
        try {
            $response = OpenAI::chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.5,
                'max_tokens'  => 300,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a travel documentation expert. Provide accurate, up-to-date information.'],
                    ['role' => 'user', 'content' => "Summarize visa requirements, entry rules, and travel documents needed for international tourists visiting {$city}, Kenya."]
                ]
            ]);

            return ['content' => trim($response->choices[0]->message->content ?? '')];
        } catch (Exception $e) {
            return ['content' => ''];
        }
    }

    /**
     * Full travel guide (used by the Build Job)
     */
    public function buildGuide(City $city): string
    {
        try {
            $prompt = <<<PROMPT
Write a compelling, SEO-friendly travel guide for {$city->name}, Kenya.

Include:
- Captivating introduction
- Best time to visit
- Top attractions
- Practical travel tips

Tone: Exciting yet informative. Maximum 450 words.
PROMPT;

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
            Log::error("AI buildGuide failed", ['city' => $city->name]);
            return "Welcome to {$city->name}. A destination full of culture, adventure, and natural beauty.";
        }
    }
}