<!DOCTYPE html>
<html>
<head>
    <title>{{ $city->name }} Travel Guide</title>
</head>

<body>

<!-- HERO -->
<section>
    <h1>{{ $city->name }}</h1>
    <p>{{ $city->country->name }}</p>
    <p>{{ $city->guide->intro_text ?? '' }}</p>
</section>

<hr>

<!-- IMAGE STRIP -->
<section>
    <h2>Explore Visually</h2>

    <div style="display:flex; gap:10px;">
        @foreach($city->images as $img)
            <img src="{{ $img->image_url }}" width="200">
        @endforeach
    </div>
</section>

<hr>

<!-- AI GUIDE -->
<section>
    <h2>Travel Guide</h2>
    <p>{!! nl2br(e($city->guide->intro_text ?? 'Generating...')) !!}</p>
</section>

<hr>

<!-- COST DASHBOARD -->
<section>
    <h2>Budget in {{ $city->name }}</h2>

    <ul>
        <li>Low: {{ $city->costs->budget_daily_low ?? '-' }}</li>
        <li>Mid: {{ $city->costs->budget_daily_mid ?? '-' }}</li>
        <li>High: {{ $city->costs->budget_daily_high ?? '-' }}</li>
    </ul>
</section>

<hr>

<!-- PLACES -->
<section>
    <h2>Things to Do</h2>

    @foreach($city->places as $place)
        <div>
            <strong>{{ $place->name }}</strong><br>
            {{ $place->category->name }}<br>
            Rating: {{ $place->rating }}
        </div>
        <hr>
    @endforeach
</section>

<hr>

<!-- HOTELS -->
<section>
    <h2>Where to Stay</h2>

    @foreach($city->hotels as $hotel)
        <div>
            <strong>{{ $hotel->name }}</strong><br>
            Rating: {{ $hotel->rating }}<br>

            <a href="{{ $hotel->affiliate_url }}" target="_blank">
                Book Hotel
            </a>
        </div>
        <hr>
    @endforeach
</section>

<hr>

<!-- WEATHER -->
<section>
    <h2>Best Time to Visit</h2>

    @foreach($city->weather as $w)
        <div>
            Month {{ $w->month }}:
            {{ $w->avg_temp_day }}°C
        </div>
    @endforeach
</section>

</body>
</html>