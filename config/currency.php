<?php

/*
| Package prices are stored in Kenya shillings (what partners charge and what the admin form takes).
| Visitors see US dollars, converted at this fixed rate. Change KES_PER_USD in .env when the market moves
| enough to matter. The default was about 129.9 on 27 Oct 2026; whole dollars are shown, so small moves do not change the page.
*/
return [
    'kes_per_usd' => (float) env('KES_PER_USD', 129),
];
