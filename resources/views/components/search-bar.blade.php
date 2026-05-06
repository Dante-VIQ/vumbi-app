<div class="search-wrapper">
    <input
        type="text"
        id="searchInput"
        placeholder="Search a destination... (e.g. Nakuru)"
        onkeyup="searchPlaces(this.value)"
    />

    <div id="searchResults"></div>
</div>

<script>
async function searchPlaces(query) {
    if (query.length < 2) return;

    let response = await fetch(`/api/search?q=${query}`);
    let data = await response.json();

    let html = '';

    // Cities
    if (data.cities.length > 0) {
        html += `<h4>Cities</h4>`;
        data.cities.forEach(city => {
            html += `
                <a href="/discover/kenya/${city.slug}">
                    ${city.name} (${city.country})
                </a><br>
            `;
        });
    }

    // Places
    if (data.places.length > 0) {
        html += `<h4>Places</h4>`;
        data.places.forEach(place => {
            html += `
                <div>
                    ${place.name} - ${place.category}
                </div>
            `;
        });
    }

    // Suggestions
    if (data.suggestions.length > 0) {
        html += `<h4>Suggestions</h4>`;
        data.suggestions.forEach(s => {
            html += `<div>${s}</div>`;
        });
    }

    document.getElementById('searchResults').innerHTML = html;
}
</script>