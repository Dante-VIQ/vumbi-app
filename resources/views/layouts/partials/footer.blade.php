<footer class="container mx-auto px-6 py-16">
    <div class="grid md:grid-cols-4 gap-12">
        <div class="md:col-span-2">
            <h3 class="text-3xl text-sunflare mb-4">Vumbi Ventures</h3>
            <p class="text-[#C4B9A6] max-w-md">
                From overlooked places, we build remarkable solutions. A digital innovation foundry rooted in the spirit of Africa.
            </p>
        </div>
        <div>
            <h4 class="text-sunflare mb-4 uppercase text-sm tracking-wider">Explore</h4>
            <ul class="space-y-2 text-[#C4B9A6]">
                <li><a href="{{ url('/ecosystem') }}" class="hover:text-sunflare">Ecosystem</a></li>
                <li><a href="{{ url('/discover') }}" class="hover:text-sunflare">Discover Africa</a></li>
                <li><a href="{{ url('/field-notes') }}" class="hover:text-sunflare">Field Notes</a></li>
                <li><a href="{{ url('/manifesto') }}" class="hover:text-sunflare">Manifesto</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sunflare mb-4 uppercase text-sm tracking-wider">Connect</h4>
            <ul class="space-y-2 text-[#C4B9A6]">
                <li><a href="{{ url('/contact') }}" class="hover:text-sunflare">Contact</a></li>
                <li><a href="#" class="hover:text-sunflare">Press</a></li>
                <li><a href="#" class="hover:text-sunflare">Partners</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-dust-mite border-opacity-20 mt-12 pt-8 text-sm text-[#C4B9A6] flex justify-between">
        <span>© {{ date('Y') }} Vumbi Ventures</span>
        <span>Dust is everywhere. We build in it.</span>
    </div>
</footer>