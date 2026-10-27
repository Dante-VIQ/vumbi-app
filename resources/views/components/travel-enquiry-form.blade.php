@props([
    'persona' => null,
    'placement' => 'contact',
    'showType' => false,
    'subject' => null,
    'messagePlaceholder' => 'Where would you like to go, and what matters most: budget, adventure, comfort, kids?',
])

@php
    $persona = \App\Support\Persona::normalize($persona);
    $defaultSubject = $subject ?? ($persona ? 'Trip enquiry: ' . \App\Support\Persona::label($persona) : '');
    $field = 'w-full px-4 py-3 bg-white border border-black/10 rounded-xl focus:outline-none focus:border-[#8B5A2B] focus:ring-2 focus:ring-[#8B5A2B]/10 transition';
@endphp

<form action="{{ route('contact.submit') }}" method="POST" class="space-y-5"
    data-gtag-event="enquiry_form_view" data-enquiry-placement="{{ $placement }}">
    @csrf
    <input type="hidden" name="persona" value="{{ old('persona', $persona) }}">
    @unless ($showType)
        <input type="hidden" name="inquiry_type" value="travel">
    @endunless

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label for="{{ $placement }}-name" class="block text-sm font-medium text-[#5C5C5C] mb-1">Full name *</label>
            <input type="text" name="name" id="{{ $placement }}-name" value="{{ old('name') }}" required autocomplete="name" class="{{ $field }}" placeholder="Jane Doe">
            @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="{{ $placement }}-email" class="block text-sm font-medium text-[#5C5C5C] mb-1">Email address *</label>
            <input type="email" name="email" id="{{ $placement }}-email" value="{{ old('email') }}" required autocomplete="email" class="{{ $field }}" placeholder="jane@example.com">
            @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label for="{{ $placement }}-phone" class="block text-sm font-medium text-[#5C5C5C] mb-1">Phone or WhatsApp (optional)</label>
            <input type="tel" name="phone" id="{{ $placement }}-phone" value="{{ old('phone') }}" autocomplete="tel" class="{{ $field }}" placeholder="+254 XXX XXX XXX">
        </div>
        @if ($showType)
            <div>
                <label for="inquiry_type" class="block text-sm font-medium text-[#5C5C5C] mb-1">I'm interested in *</label>
                <select name="inquiry_type" id="inquiry_type" required class="{{ $field }}">
                    <option value="">-- Please select --</option>
                    <option value="travel" {{ old('inquiry_type', $persona ? 'travel' : '') == 'travel' ? 'selected' : '' }}>Planning a safari or trip</option>
                    <option value="partnership" {{ old('inquiry_type') == 'partnership' ? 'selected' : '' }}>Partnering as an operator or guide</option>
                    <option value="other" {{ old('inquiry_type') == 'other' ? 'selected' : '' }}>Other inquiry</option>
                </select>
                @error('inquiry_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @else
            <div>
                <label for="{{ $placement }}-month" class="block text-sm font-medium text-[#5C5C5C] mb-1">Travel month</label>
                <input type="month" name="travel_month" id="{{ $placement }}-month" value="{{ old('travel_month') }}" min="{{ now()->format('Y-m') }}" class="{{ $field }}">
                @error('travel_month') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @endif
    </div>

    @if ($showType)
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label for="{{ $placement }}-month" class="block text-sm font-medium text-[#5C5C5C] mb-1">Travel month</label>
                <input type="month" name="travel_month" id="{{ $placement }}-month" value="{{ old('travel_month') }}" min="{{ now()->format('Y-m') }}" class="{{ $field }}">
                @error('travel_month') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-[#5C5C5C] mb-1">Subject</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $defaultSubject) }}" class="{{ $field }}" placeholder="How can we help?">
            </div>
        </div>
    @else
        <input type="hidden" name="subject" value="{{ old('subject', $defaultSubject) }}">
    @endif

    <div class="grid md:grid-cols-2 gap-5">
        <div>
            <label for="{{ $placement }}-group" class="block text-sm font-medium text-[#5C5C5C] mb-1">How many travellers?</label>
            <input type="number" name="group_size" id="{{ $placement }}-group" value="{{ old('group_size') }}" min="1" max="200" class="{{ $field }}" placeholder="e.g. 4">
            @error('group_size') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
        <div>
            <label for="{{ $placement }}-budget" class="block text-sm font-medium text-[#5C5C5C] mb-1">Budget per person</label>
            <select name="budget_range" id="{{ $placement }}-budget" class="{{ $field }}">
                <option value="">Not sure yet</option>
                @foreach (['budget' => 'Under $250', 'mid' => '$250 to $700', 'premium' => '$700 and above'] as $key => $label)
                    <option value="{{ $key }}" {{ old('budget_range') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('budget_range') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label for="{{ $placement }}-message" class="block text-sm font-medium text-[#5C5C5C] mb-1">Message *</label>
        <textarea name="message" id="{{ $placement }}-message" rows="4" required class="{{ $field }}" placeholder="{{ $messagePlaceholder }}">{{ old('message') }}</textarea>
        @error('message') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-start gap-3">
        <input type="checkbox" name="consent" id="{{ $placement }}-consent" required class="mt-0.5 w-4 h-4 text-[#8B5A2B] rounded border-gray-300 focus:ring-[#8B5A2B]">
        <label for="{{ $placement }}-consent" class="text-xs text-[#5C5C5C]">
            I agree to be contacted about this inquiry. We protect your data and never spam. See our <a href="{{ route('privacy') }}" class="underline">privacy policy</a>.
        </label>
    </div>

    <button type="submit" data-gtag-event="enquiry_submit_click" data-gtag-params='{"placement":"{{ $placement }}"}'
        class="w-full py-3.5 rounded-xl text-base font-semibold bg-[#8B5A2B] hover:bg-[#5C3A1E] text-white transition shadow-md">
        Send my enquiry
    </button>
    <p class="text-xs text-center text-[#5C5C5C]">We reply within 24 business hours.</p>
</form>
