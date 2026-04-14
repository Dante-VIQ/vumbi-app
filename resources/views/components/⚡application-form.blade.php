<?php

use Livewire\Component;
use App\Models\Doctor;
use Livewire\Attributes\Computed;

new class extends Component {
   
    public AppointmentForm $form;

    public function submitForm()
    {
        $this->form->validate();

        // send email

        session()->flash('success', 'Appointment Created');

        $this->form->reset();
    }
};
?>

<div>
    <form wire:submit.prevent="submitContact" class="space-y-6">
        <div>
            <label class="block text-sm font-medium mb-2">Name</label>
            <input type="text" wire:model="name" required
                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" wire:model="email" required
                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white">
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Message</label>
            <textarea rows="4" wire:model="message" required
                class="w-full px-4 py-3 rounded-xl border border-[#E5E0D9] focus:outline-none focus:ring-2 focus:ring-[#8B5A2B] bg-white"></textarea>
            @error('message')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit"
            class="w-full bg-[#8B5A2B] text-white px-6 py-3 rounded-xl hover:bg-[#6B421F] transition font-medium inline-flex items-center justify-center gap-2"
            wire:loading.attr="disabled">
            <span wire:loading.remove>Send Message <i class="fas fa-paper-plane"></i></span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>