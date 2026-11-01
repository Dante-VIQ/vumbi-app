# AEO / GEO playbook for Vumbi Ventures

Goal: when someone asks an AI assistant or search engine "What is Vumbi Ventures?" (or a travel question we can answer), the answer is correct and links to us.

## The one answer, said the same way everywhere
`config/seo.php` -> `definition`. It is used on the About page, the homepage, `/faq`, `llms.txt` and the Organization schema. AI systems trust a fact more when they see it repeated consistently, so change it in one place only.

## What is on the site now
- `/faq`: 20-ish questions in `config/faq.php`, each answer standalone, published as FAQPage schema. First question is "What is Vumbi Ventures?".
- About page: the answer at the top, "How we work", a founder note (`config/about.php`), AboutPage schema pointing at the Organization.
- Schema: one Organization (TravelAgency) with a stable `@id`, founder, `knowsAbout`, contact details; WebSite; Article + FAQPage on Field Notes that have a FAQ section; Product + TouristTrip on trips; TouristDestination on destinations; BreadcrumbList everywhere.
- `llms.txt`, a sitemap that includes `/faq`, and crawler access for the AI search bots in `robots.txt`.
- `php artisan ai:report` shows which AI crawlers fetched the site; GA gets an `ai_referral` event.

## Off-site: what moves the needle most (do these)
Assistants answer from what other sites say about you, not only from your own pages. The name "Vumbi" collides with other businesses, so make the entity unambiguous.
1. **Google Business Profile** for the Nakuru base (name, address, phone, website, category "Travel agency"). Match `config/seo.php` exactly.
2. **Consistent profiles**: Instagram, Facebook, TikTok, LinkedIn with the same one-line description and a link to vumbiventures.com. Add the real URLs to `SEO_SAME_AS` in `.env` (comma separated).
3. **Third-party mentions**: Crunchbase or similar company profile, local directories (Kenya tourism / business listings), and guest posts or interviews that name "Vumbi Ventures" with the same description.
4. **Reviews**: ask real travellers to review on Google and TripAdvisor. Do not add review markup to the site until you have real reviews to show.
5. **Wikidata**: only if you can meet its notability rules with independent sources. Skip otherwise.

## Content habits that get cited
- Put the direct answer in the first two sentences, then the detail.
- Use questions as headings ("How long does it take to climb Mount Kenya?") and answer them plainly.
- Show an author and a date, and update old guides (the page now shows "Updated" when edited).
- Prefer specific, checkable facts (distances, months, permit rules, prices with a date) over adjectives.
- Every Field Note should end with a short FAQ section; the site turns it into FAQPage markup automatically.

## Monthly test (15 minutes)
Ask ChatGPT, Perplexity, Gemini and Claude, in a fresh session each:
1. What is Vumbi Ventures?
2. Who runs Vumbi Ventures and where is it based?
3. Is Vumbi Ventures a legitimate safari planner?
4. Best way to plan a budget trip to Kenya as a student?
5. A topic from your Field Notes (for example "Who was Abubakari II?").
Record: is it correct, are we named, is vumbiventures.com cited. Fix wrong answers by correcting the source (your pages, then profiles), not by arguing with the assistant. Then run `php artisan ai:report --days=30`.

## Do not
- Add FAQ or review markup for content that is not visible on the page.
- Invent statistics, awards, years in business or team size.
- Hide text for bots only.
