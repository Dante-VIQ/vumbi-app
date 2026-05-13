@props(['package' => null])

<div>
    <label class="block text-sm font-medium mb-1">Title *</label>
    <input name="title" value="{{ old('title', $package->title ?? '') }}" required
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Location *</label>
    <input name="location" value="{{ old('location', $package->location ?? '') }}" required
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Slug (auto‑generated if left blank)</label>
    <input name="slug" value="{{ old('slug', $package->slug ?? '') }}"
           class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Description *</label>
    <textarea name="description" rows="4" required
              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">{{ old('description', $package->description ?? '') }}</textarea>
</div>

<!-- Price & Type -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Price (KES) *</label>
        <input name="price" type="number" step="0.01" value="{{ old('price', $package->price ?? '') }}" required
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Type *</label>
        <select name="type" required class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
            @foreach(['transfer' => 'Transfer', 'safari' => 'Safari', 'tour' => 'Tour', 'beach' => 'Beach', 'trekking' => 'Trekking'] as $val => $label)
                <option value="{{ $val }}" {{ old('type', $package->type ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Duration -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Days</label>
        <input name="duration_days" type="number" value="{{ old('duration_days', $package->duration_days ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Nights</label>
        <input name="duration_nights" type="number" value="{{ old('duration_nights', $package->duration_nights ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
</div>

<!-- Difficulty, Group Size -->
<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Difficulty</label>
        <select name="difficulty" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
            <option value="">{{ '—' }}</option>
            @foreach(['easy', 'moderate', 'challenging'] as $diff)
                <option value="{{ $diff }}" {{ old('difficulty', $package->difficulty ?? '') == $diff ? 'selected' : '' }}>{{ ucfirst($diff) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Min Group</label>
        <input name="group_size_min" type="number" value="{{ old('group_size_min', $package->group_size_min ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Max Group</label>
        <input name="group_size_max" type="number" value="{{ old('group_size_max', $package->group_size_max ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
</div>

<!-- Vehicle & Image -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Vehicle Type</label>
        <input name="vehicle_type" value="{{ old('vehicle_type', $package->vehicle_type ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Image URL</label>
        <input name="image" value="{{ old('image', $package->image ?? '') }}"
               class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">
    </div>
</div>

<!-- Itinerary -->
<div>
    <label class="block text-sm font-medium mb-1">Itinerary (one line per day)</label>
    <textarea name="itinerary_text" rows="5"
              class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">{{ old('itinerary_text', isset($package) ? implode("\n", (array) $package->itinerary) : '') }}</textarea>
    <small class="text-zinc-500">Example: Day 1: Nairobi to Masai Mara, afternoon game drive</small>
</div>

<!-- Included / Excluded -->
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Included (one line each)</label>
        <textarea name="included_text" rows="4"
                  class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">{{ old('included_text', isset($package) ? implode("\n", (array) $package->included) : '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Excluded (one line each)</label>
        <textarea name="excluded_text" rows="4"
                  class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-white">{{ old('excluded_text', isset($package) ? implode("\n", (array) $package->excluded) : '') }}</textarea>
    </div>
</div>

<!-- Active checkbox -->
<div class="flex items-center gap-2">
    <input type="checkbox" name="active" value="1" {{ old('active', $package->active ?? true) ? 'checked' : '' }} class="w-5 h-5">
    <label class="text-sm">Active (visible to customers)</label>
</div>