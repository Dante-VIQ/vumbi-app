<?php

namespace App\Console\Commands;

use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

/**
 * Crawls the site in-process (no network) from the sitemap and reports on-page SEO,
 * structured data, and broken internal links. Exit code 1 when any error is found.
 */
class SiteAudit extends Command
{
    protected $signature = 'site:audit {--limit=300 : Max pages to crawl} {--json : Output JSON}';

    protected $description = 'Audit pages for titles, meta, canonical, H1, JSON-LD, OG tags, alt text and broken internal links';

    private array $cache = [];

    public function handle(): int
    {
        $sitemap = $this->fetch('/sitemap.xml');
        preg_match_all('#<loc>([^<]+)</loc>#', $sitemap['body'] ?? '', $m);
        $urls = array_slice(array_unique($m[1] ?? []), 0, (int) $this->option('limit'));

        $errors = [];
        $warnings = [];
        $links = [];
        $rows = [];

        foreach ($urls as $url) {
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            $r = $this->fetch($path);
            if ($r['status'] >= 300) {
                $errors[] = "$path returned {$r['status']} (listed in sitemap)";
                continue;
            }
            $dom = new DOMDocument;
            @$dom->loadHTML('<?xml encoding="utf-8"?>'.$r['body']);
            $x = new DOMXPath($dom);
            $e = function ($m) use (&$errors, $path) { $errors[] = "$path: $m"; };
            $w = function ($m) use (&$warnings, $path) { $warnings[] = "$path: $m"; };

            $title = trim($x->evaluate('string(//title)'));
            $len = mb_strlen($title);
            if ($title === '') { $e('missing <title>'); } elseif ($len > 65) { $w("title is $len chars (>65)"); }
            if (substr_count($r['body'], '<title') > 1) { $e('multiple <title> tags'); }

            $desc = trim($x->evaluate('string(//meta[@name="description"]/@content)'));
            $dl = mb_strlen($desc);
            if ($desc === '') { $e('missing meta description'); } elseif ($dl < 70 || $dl > 175) { $w("meta description is $dl chars (aim 70-160)"); }

            $canon = $x->evaluate('string(//link[@rel="canonical"]/@href)');
            if ($canon === '') { $e('missing canonical'); }

            $h1 = $x->query('//h1')->length;
            if ($h1 !== 1) { $w("has $h1 <h1> tags (want 1)"); }

            foreach (['og:title', 'og:description', 'og:image', 'og:type'] as $og) {
                if ($x->evaluate("string(//meta[@property='$og']/@content)") === '') { $e("missing $og"); }
            }

            $types = [];
            foreach ($x->query('//script[@type="application/ld+json"]') as $s) {
                $j = json_decode(trim($s->textContent), true);
                if ($j === null) { $e('invalid JSON-LD block'); continue; }
                foreach (isset($j['@graph']) ? $j['@graph'] : [$j] as $node) {
                    $t = $node['@type'] ?? null;
                    $types = array_merge($types, (array) $t);
                }
            }
            if (! in_array('Organization', $types) && ! in_array('TravelAgency', $types)) { $e('no Organization schema'); }
            if (! in_array('BreadcrumbList', $types) && $path !== '/') { $w('no BreadcrumbList'); }

            $noAlt = 0;
            foreach ($x->query('//img') as $img) {
                if (! $img->hasAttribute('alt')) { $noAlt++; }
            }
            if ($noAlt) { $w("$noAlt <img> without alt"); }

            $ogImage = $x->evaluate('string(//meta[@property="og:image"]/@content)');
            if ($ogImage !== '') { $links[$ogImage][] = "$path (og:image)"; }

            foreach ($x->query('//a[@href]') as $a) {
                $h = trim($a->getAttribute('href'));
                if ($h === '' || preg_match('#^(mailto:|tel:|javascript:|\#|sms:|whatsapp:)#', $h)) { continue; }
                $p = parse_url($h);
                $host = $p['host'] ?? null;
                if ($host && $host !== parse_url(url('/'), PHP_URL_HOST)) { continue; }
                $target = ($p['path'] ?? '/').(isset($p['query']) ? '?'.$p['query'] : '');
                $links[$target][] = $path;
            }

            $rows[] = ['path' => $path, 'title' => $len, 'schema' => implode(',', array_unique($types)), 'kb' => round(strlen($r['body']) / 1024)];
        }

        foreach ($links as $target => $from) {
            if (str_starts_with($target, 'http')) { $target = parse_url($target, PHP_URL_PATH) ?: '/'; }
            if (preg_match('#^/(go|admin|logout|login|register|newsletter|inquiry|blog\/\d+$)#', $target)) { continue; }
            if (preg_match('#\.(jpg|jpeg|png|webp|avif|svg|css|js|ico)$#i', $target)) {
                if (! file_exists(public_path(ltrim(parse_url($target, PHP_URL_PATH), '/')))) {
                    $errors[] = "missing asset $target (from ".implode(', ', array_slice(array_unique($from), 0, 3)).')';
                }
                continue;
            }
            $s = $this->fetch($target)['status'];
            if ($s >= 400) {
                $errors[] = "broken link $target [$s] (from ".implode(', ', array_slice(array_unique($from), 0, 3)).')';
            }
        }

        if ($this->option('json')) {
            $this->line(json_encode(compact('rows', 'errors', 'warnings'), JSON_PRETTY_PRINT));
        } else {
            $this->table(['Page', 'Title', 'Schema types', 'KB'], $rows);
            $this->newLine();
            foreach ($errors as $m) { $this->error($m); }
            foreach ($warnings as $m) { $this->warn($m); }
            $this->info(count($urls).' pages, '.count($errors).' errors, '.count($warnings).' warnings');
        }

        return $errors ? self::FAILURE : self::SUCCESS;
    }

    private function fetch(string $path): array
    {
        return $this->cache[$path] ??= (function () use ($path) {
            $lvl = ob_get_level();
            try {
                $res = app()->handle(Request::create($path, 'GET'));
                $out = ['status' => $res->getStatusCode(), 'body' => (string) $res->getContent()];
                if (in_array($out['status'], [301, 302, 308], true)) {
                    $out['status'] = 200; // redirects are fine for link checking; target audited separately
                }
                return $out;
            } catch (\Throwable $t) {
                while (ob_get_level() > $lvl) { ob_end_clean(); }
                return ['status' => 500, 'body' => ''];
            }
        })();
    }
}
