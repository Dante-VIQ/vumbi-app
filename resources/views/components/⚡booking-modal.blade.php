<?php

use Livewire\Component;
use App\Models\PartnerPackage;
use App\Services\BookingService;
use App\Livewire\Forms\BookingFormObject;

new class extends Component {

    public ?PartnerPackage $partnerPackage = null;
    public int $partnerPackageId;
    public bool $submitted = false;
    public BookingFormObject $form;
    protected BookingService $bookingService;

    public function mount(int $partnerPackageId)
    {
        $this->partnerPackageId = $partnerPackageId;
        $this->partnerPackage = PartnerPackage::findOrFail($partnerPackageId);
    }

    public function boot(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function submit()
    {
        $this->form->validate();

        // Re-fetch the partner package if needed
        $partnerPackage = PartnerPackage::findOrFail($this->partnerPackageId);

        $data = $this->form->all();
        $data['partner_package_id'] = $partnerPackage->id;

        $this->bookingService->createLead($data);

        $this->submitted = true;
        $this->form->reset();
        $this->dispatch('lead-captured');
    }
};
?>

<div class="max-w-2xl mx-auto p-6 bg-zinc-950 rounded-3xl border border-zinc-800 my-20">
    @if($submitted)
        <div class="text-center">
            <h3 class="text-2xl font-bold text-green-400">Thank You!</h3>
            <p class="text-zinc-300 mt-4">
                We've received your request for <strong>{{ $partnerPackage?->title }}</strong>.
            </p>
            <p class="text-zinc-400 text-sm mt-2">
                Our team will confirm availability with our local partner and reach out within 24 hours via WhatsApp or email.
            </p>
        </div>
    @else
        <form wire:submit.prevent="submit">
            @csrf
            <h3 class="text-xl font-semibold mb-1">Book {{ $partnerPackage?->title }}</h3>
            <p class="text-slate-200 text-sm mb-6">We'll confirm with our local partner and get back to you fast.</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="text" wire:model="form.first_name" placeholder="Name *"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    @error('form.first_name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="tel" wire:model="form.phone" placeholder="WhatsApp number *"
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white">
                    @error('form.phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <input type="email" wire:model="form.email" placeholder="Email (optional)"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white mt-4">
            @error('form.email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror

            <input type="date" wire:model="form.start_date" placeholder="Preferred date"
                class="w-full bg-zinc-900 border border-zinc-700 rounded-xl p-3 text-white mt-4">
            @error('form.start_date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror

            <button type="submit" wire:loading.attr="disabled"
                class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-xl transition">
                <span wire:loading.remove>Send Booking Request</span>
                <span wire:loading>Sending...</span>
            </button>
            <p class="text-zinc-600 text-xs text-center mt-3">No payment required now — we confirm availability first.</p>
        </form>
    @endif
</div>