<?php

use Livewire\Component;
use App\Models\Lead;
use App\Models\PartnerPackage;
use App\Services\BookingService;
use App\Livewire\Forms\BookingFormObject;

new class extends Component {
    public PartnerPackage $partnerPackage;
    public int $step = 1;

    // Step 1
    public ?string $start_date = null;
    public ?string $end_date = null;
    public int $adults = 2;
    public int $children = 0;
    public ?string $special_occasion = null; // honeymoon, family, etc.

    // Step 2
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $country = 'KE';

    // Step 3
    public ?string $message = '';
    public bool $whatsapp_opt_in = true;

    public BookingFormObject $form;

    protected function rules(): array
    {
        return [
            'start_date' => 'required|date|after:today',
            'adults' => 'required|integer|min:1',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|min:10',
            'country' => 'required|string|size:2',
        ];
    }

    public function mount(PartnerPackage $partnerPackage)
    {
        $this->partnerPackage = $partnerPackage;
    }


    protected BookingService $bookingService;

    public function boot(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function nextStep()
    {
        $this->form->validateStep($this->step);
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        if (!isset($this->partnerPackage)) {
            throw new \Exception('PartnerPackage not initialized.');
        }
        // Full validation
        $this->form->validate();

        // Prepare data array
        $data = $this->form->all();
        $data['partner_package_id'] = $this->partnerPackage->id;
        // $data['partner_id'] = $this->partnerPackage->partner_id;

        $lead = $this->bookingService->createLead($data);

        $this->step = 'thankyou';
    }
};
?>

<div class="max-w-2xl mx-auto p-6 bg-zinc-950 rounded-3xl border border-zinc-800 my-20">
    @if($step === 'thankyou')
        <div class="text-center">
            <h3 class="text-2xl font-bold text-green-400">Thank You!</h3>
            <p class="text-zinc-300 mt-4">
                We've received your request for <strong>{{ $partnerPackage->title }}</strong>.
            </p>
            <p class="text-zinc-400 text-sm mt-2">
                We'll get back to you within 24 hours via WhatsApp or email.
            </p>
        </div>
    @else
        <!-- Progress bar -->
        <div class="flex justify-between mb-8">
            <span class="text-sm font-medium {{ $step == 1 ? 'text-green-400' : 'text-zinc-500' }}">
                1. Trip
            </span>
            <span class="text-sm font-medium {{ $step == 2 ? 'text-green-400' : 'text-zinc-500' }}">
                2. Your Details
            </span>
            <span class="text-sm font-medium {{ $step == 3 ? 'text-green-400' : 'text-zinc-500' }}">
                3. Review
            </span>
        </div>
        <div x-data="{ step: @entangle('step') }" x-show="step === 1">
            <!-- Step 1: Trip Preferences -->
            <div x-show="step === 1" x-transition>
                <h3 class="text-xl font-semibold mb-4">When &amp; Who</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-zinc-400 text-sm">Start Date *</label>
                        <input type="date" wire:model="form.start_date"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                        @error('form.start_date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-zinc-400 text-sm">End Date (optional)</label>
                        <input type="date" wire:model="form.end_date"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-zinc-400 text-sm">Adults *</label>
                        <input type="number" min="1" wire:model="form.adults"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                        @error('form.adults') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-zinc-400 text-sm">Children</label>
                        <input type="number" min="0" wire:model="form.children"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    </div>
                    <div>
                        <label class="block text-zinc-400 text-sm">Occasion</label>
                        <select wire:model="form.special_occasion"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                            <option value="">None</option>
                            <option value="honeymoon">Honeymoon</option>
                            <option value="family">Family Trip</option>
                            <option value="solo">Solo</option>
                            <option value="group">Group</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contact Details -->
            <div x-show="step === 2" x-transition>
                <h3 class="text-xl font-semibold mb-4">How Can We Reach You?</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-zinc-400 text-sm">First Name *</label>
                        <input type="text" wire:model="form.first_name"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                        @error('form.first_name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-zinc-400 text-sm">Last Name *</label>
                        <input type="text" wire:model="form.last_name"
                            class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                        @error('form.last_name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-zinc-400 text-sm">Email *</label>
                    <input type="email" wire:model="form.email"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    @error('form.email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4">
                    <label class="block text-zinc-400 text-sm">Phone (WhatsApp preferred) *</label>
                    <input type="tel" wire:model="form.phone" placeholder="+254 7XX XXX XXX"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    @error('form.phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4">
                    <label class="block text-zinc-400 text-sm">Country *</label>
                    <select wire:model="form.country"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                        <option value="KE">Kenya</option>
                        <option value="US">United States</option>
                        <option value="GB">United Kingdom</option>
                        <!-- add more from a countries helper -->
                    </select>
                    @error('form.country') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Step 3: Review & Submit -->
            <div x-show="step === 3" x-transition>
                <h3 class="text-xl font-semibold mb-4">Almost Done</h3>
                <div class="bg-zinc-900 rounded-xl p-4 space-y-2 text-sm">
                    <p><strong class="text-zinc-400">Tour:</strong> {{ $partnerPackage }}</p>
                    <p><strong class="text-zinc-400">Dates:</strong>
                        {{ $form->start_date ?? 'Not set' }} – {{ $form->end_date ?? 'Open' }}
                    </p>
                    <p><strong class="text-zinc-400">Travelers:</strong>
                        {{ $form->adults }} adults, {{ $form->children }} children
                    </p>
                    <p><strong class="text-zinc-400">Name:</strong>
                        {{ $form->first_name }} {{ $form->last_name }}
                    </p>
                    <p><strong class="text-zinc-400">Contact:</strong>
                        {{ $form->email }} / {{ $form->phone }}
                    </p>
                    <p><strong class="text-zinc-400">Country:</strong> {{ $form->country }}</p>
                </div>
                <div class="mt-4">
                    <label class="block text-zinc-400 text-sm">Any special requests? (optional)</label>
                    <textarea wire:model="form.message" rows="3"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white"></textarea>
                </div>
                <div class="flex items-center mt-4">
                    <input type="checkbox" wire:model="form.whatsapp_opt_in" class="rounded bg-zinc-900 border-zinc-700">
                    <label class="ml-2 text-sm text-zinc-400">Send trip details via WhatsApp</label>
                </div>
                <button wire:click="submit"
                    class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-xl transition">
                    Send Inquiry
                </button>
            </div>
        </div>
        <!-- Navigation -->
        @if($step !== 3)
            <div class="flex justify-between mt-8">
                @if($step > 1)
                    <button wire:click="previousStep" class="px-6 py-2 text-zinc-400 hover:text-white transition">
                        ← Back
                    </button>
                @else
                    <span></span>
                @endif
                <button wire:click="nextStep"
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition">
                    Continue →
                </button>
            </div>
        @endif
    @endif
</div>