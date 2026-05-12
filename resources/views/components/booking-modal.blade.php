<div x-data="bookingModal()" x-show="open" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-xl" @click.away="open = false">
        <h3 class="text-lg font-bold mb-4">Book This Trip</h3>
        <form @submit.prevent="submitBooking">
            <input type="hidden" name="package_id" x-model="packageId">
            <div class="mb-3">
                <label class="block text-sm font-medium">Your Name *</label>
                <input type="text" x-model="form.name" required class="w-full border rounded p-2 mt-1">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Phone Number *</label>
                <input type="text" x-model="form.phone" required class="w-full border rounded p-2 mt-1">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Email (optional)</label>
                <input type="email" x-model="form.email" class="w-full border rounded p-2 mt-1">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Notes (optional)</label>
                <textarea x-model="form.notes" rows="2" class="w-full border rounded p-2 mt-1"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="open = false" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Send Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bookingModal() {
        return {
            open: false,
            packageId: null,
            form: { name: '', phone: '', email: '', notes: '' },
            openForPackage(pkgId) {
                this.packageId = pkgId;
                this.form = { name: '', phone: '', email: '', notes: '' };
                this.open = true;
            },
            async submitBooking() {
                const res = await fetch('/api/partner-leads', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        customer_name: this.form.name,
                        customer_phone: this.form.phone,
                        customer_email: this.form.email,
                        package_id: this.packageId,
                        notes: this.form.notes
                    })
                });
                if (res.ok) {
                    alert('Request sent! We’ll contact you soon.');
                    this.open = false;
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        }
    }
</script>