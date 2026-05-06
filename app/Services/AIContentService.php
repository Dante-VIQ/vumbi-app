<?php

namespace App\Services;

use App\Models\City;
use App\Services\ApiClients\OpenAIClient;

class AIContentService
{
    public function __construct(
        private OpenAIClient $client
    ) {}

    public function buildGuide(City $city): string
    {
        $prompt = "Write a travel guide for {$city->name}, Kenya";

        return $this->client->generate($prompt);
    }
}