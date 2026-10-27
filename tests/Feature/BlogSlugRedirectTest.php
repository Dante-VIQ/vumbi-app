<?php

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(fn () => $this->withoutVite());

function slugPost(string $title, array $attrs = []): Blog
{
    static $user;
    $user ??= User::withoutEvents(fn () => User::factory()->create());

    return Blog::create(array_merge([
        'user_id' => $user->id,
        'category' => 'culture',
        'title' => $title,
        'description' => '<p>Body text.</p>',
    ], $attrs));
}

test('new posts get a readable unique slug', function () {
    $a = slugPost('Mount Kenya: A Climbing Guide!');
    $b = slugPost('Mount Kenya: A Climbing Guide!');

    expect($a->slug)->toBe('mount-kenya-a-climbing-guide')
        ->and($b->slug)->toBe('mount-kenya-a-climbing-guide-2')
        ->and($a->url)->toBe(url('/blog/mount-kenya-a-climbing-guide'));
});

test('a title that is only digits never produces an all-digit slug', function () {
    expect(slugPost('2026')->slug)->toBe('post-2026')
        ->and(ctype_digit(slugPost('!!!')->slug))->toBeFalse();
});

test('editing a title does not change the published URL', function () {
    $post = slugPost('Original Title');
    $post->update(['title' => 'A Completely New Title']);

    expect($post->fresh()->slug)->toBe('original-title');
});

test('old numeric URLs 301 to the slug URL and keep the query string', function () {
    $post = slugPost('How to Plan a Safari');

    $this->get("/blog/{$post->id}")->assertStatus(301)->assertRedirect(url('/blog/how-to-plan-a-safari'));
    $this->get("/blog/{$post->id}?utm_source=newsletter&x=1")
        ->assertStatus(301)->assertRedirect(url('/blog/how-to-plan-a-safari?utm_source=newsletter&x=1'));
});

test('slug URLs render the article with the slug as canonical', function () {
    $post = slugPost('How to Plan a Safari');

    $this->get($post->url)->assertOk()->assertSee('How to Plan a Safari')
        ->assertSee('<link rel="canonical" href="' . $post->url . '"', false);
});

test('unknown ids and slugs return 404', function () {
    $this->get('/blog/99999')->assertNotFound();
    $this->get('/blog/no-such-post')->assertNotFound();
});

test('sitemap and llms.txt list slug URLs, not numeric ones', function () {
    $post = slugPost('Serengeti Migration Guide');

    $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
    expect($sitemap)->toContain('/blog/serengeti-migration-guide')->not->toContain('/blog/' . $post->id . '<');

    $this->get('/llms.txt')->assertSee('/blog/serengeti-migration-guide', false);
});

test('the migration backfills slugs for existing rows without duplicates', function () {
    $migration = include database_path('migrations/2026_10_27_210000_add_slug_to_blogs_table.php');
    $migration->down();

    foreach (['Kilimanjaro Guide', 'Kilimanjaro Guide', '2025', ''] as $title) {
        DB::table('blogs')->insert(['user_id' => 1, 'category' => 'x', 'title' => $title, 'description' => '<p>x</p>', 'created_at' => now(), 'updated_at' => now()]);
    }
    $migration->up();

    $slugs = DB::table('blogs')->orderBy('id')->pluck('slug')->all();
    expect($slugs[0])->toBe('kilimanjaro-guide')->and($slugs[1])->toBe('kilimanjaro-guide-2')
        ->and($slugs[2])->toBe('post-2025')
        ->and(array_unique($slugs))->toHaveCount(4)
        ->and(collect($slugs)->contains(fn ($s) => $s === '' || ctype_digit($s)))->toBeFalse();
});
