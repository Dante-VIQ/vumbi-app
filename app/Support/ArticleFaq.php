<?php

namespace App\Support;

use DOMDocument;
use DOMXPath;

/**
 * Pulls question/answer pairs out of an article's HTML so they can be published
 * as FAQPage markup. Looks for an <h2> containing "Frequently Asked Questions" (or "FAQ"),
 * then each following <h3> (the question) with the paragraphs/lists up to the next heading (the answer).
 * Returns [] when the article has no such section, so nothing is invented.
 */
final class ArticleFaq
{
    /** @return array<int, array{q: string, a: string}> */
    public static function extract(?string $html, int $max = 10): array
    {
        $html = trim((string) $html);
        if ($html === '' || stripos($html, '<h3') === false) {
            return [];
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?><div id="root">' . $html . '</div>');
        libxml_clear_errors();

        $root = (new DOMXPath($dom))->query('//*[@id="root"]')->item(0);
        if (! $root) {
            return [];
        }

        $inFaq = false;
        $faqs = [];
        $question = null;
        $answer = '';

        $flush = function () use (&$faqs, &$question, &$answer) {
            $a = trim(preg_replace('/\s+/', ' ', $answer));
            if ($question !== null && $a !== '') {
                $faqs[] = ['q' => $question, 'a' => $a];
            }
            $question = null;
            $answer = '';
        };

        foreach ($root->childNodes as $node) {
            if ($node->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }
            $tag = strtolower($node->nodeName);
            $text = trim(preg_replace('/\s+/', ' ', $node->textContent));

            if ($tag === 'h2') {
                $flush();
                $inFaq = (bool) preg_match('/frequently asked questions|^faqs?\b/i', $text);

                continue;
            }
            if (! $inFaq) {
                continue;
            }
            if ($tag === 'h3') {
                $flush();
                $question = rtrim($text);

                continue;
            }
            if ($question !== null) {
                $answer .= ' ' . $text;
            }
        }
        $flush();

        return array_slice($faqs, 0, $max);
    }
}
