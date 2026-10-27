<?php

namespace App\Support;

final class LeadMagnet
{
    /** @return array{title: string, description: string, url: string} */
    public static function for(?string $persona): array
    {
        $cfg = config("lead_magnets.personas.{$persona}") ?: config('lead_magnets.default');

        return [
            'title' => (string) ($cfg['title'] ?? 'Kenya first-safari checklist'),
            'description' => (string) ($cfg['description'] ?? ''),
            'url' => isset($cfg['url']) ? (string) $cfg['url'] : route($cfg['route'] ?? 'safari-checklist'),
        ];
    }
}
