# Growth testing: one change at a time

Why: with a small audience, changing several things at once means you can't tell what worked.

## The rule
1. Pick **one** change (a headline, a button label, the quiz position on the homepage).
2. Write it in the log below with today's date and the **one number** you expect to move.
3. Leave everything else alone for **at least 14 days** and until you have **at least 100 visitors** to the page (more if the number is a rate).
4. Run `php artisan growth:review --days=14`, record the result, then keep or revert the change.
5. Only then start the next change.

## Where the numbers come from
- Google Analytics events: `persona_selected`, `quiz_start_click`, `quiz_complete`, `persona_card_click`, `enquiry_submit_click`, `whatsapp_click`, `newsletter_signup`, `ai_referral`. Persona is a user property.
- Database (`php artisan growth:review`): quiz answers, newsletter signups by persona and source, enquiries and quotes by persona, affiliate clicks by persona.

## Log

| Start | Change | Number to watch | Baseline | Result after 14+ days | Decision |
|-------|--------|-----------------|----------|------------------------|----------|
|       |        |                 |          |                        |          |

## Day-60 review
Run `php artisan growth:review --days=60` and answer:
- Which persona brings the most enquiries per visit, and which brings the fewest?
- Do quiz finishers enquire more than visitors who skip it?
- Which signup placement (`source`) gives the most subscribers, and how many open the follow-ups?
- Are AI crawlers fetching the site (`php artisan ai:report`) and are `ai_referral` visits arriving?
- Keep, change or drop each Phase 3-5 feature, one decision at a time.
