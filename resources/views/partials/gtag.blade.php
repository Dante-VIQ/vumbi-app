{{-- Google tag for standalone pages that don't extend layouts.app. Production only, same property as the layouts. --}}
@production
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JDQTSGVSYS"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-JDQTSGVSYS');
    </script>
@endproduction
