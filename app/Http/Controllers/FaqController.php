<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class FaqController extends Controller
{
    /** @return array<string, array<int, array{q: string, a: string}>> sections with every answer filled in */
    public static function sections(): array
    {
        $sections = [];

        foreach (config('faq', []) as $heading => $items) {
            foreach ($items as $item) {
                $answer = ! empty($item['definition']) ? (string) config('seo.definition') : (string) ($item['a'] ?? '');
                if ($answer === '') {
                    continue;
                }
                $sections[$heading][] = ['q' => $item['q'], 'a' => $answer];
            }
        }

        // "Who is behind it?" only when a founder is set up, so we never name someone who is not.
        $founder = config('about.founder');
        if (! empty($founder['name']) && ! empty($founder['note']) && isset($sections['About Vumbi Ventures'])) {
            $role = ! empty($founder['role']) ? strtolower($founder['role']) : 'founder';
            array_splice($sections['About Vumbi Ventures'], 1, 0, [[
                'q' => 'Who runs Vumbi Ventures?',
                'a' => "Vumbi Ventures was started by {$founder['name']}, its {$role}, a self-taught developer based in Kenya. You can read the founder's note on the About page.",
            ]]);
        }

        return $sections;
    }

    public function __invoke()
    {
        $sections = self::sections();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            '@id' => route('faq') . '#faq',
            'url' => route('faq'),
            'name' => 'Vumbi Ventures: frequently asked questions',
            'isPartOf' => ['@id' => url('/') . '#website'],
            'about' => ['@id' => url('/') . '#organization'],
            'mainEntity' => collect($sections)->flatten(1)->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ])->values()->all(),
        ];

        return view('pages.faq', ['sections' => $sections, 'pageSchemas' => [$schema]]);
    }
}
