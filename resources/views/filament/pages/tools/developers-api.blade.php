<x-filament-panels::page>
    <div class="flex flex-col">
        {{-- Developers API --}}
        {{ $this->developersAPIForm }}
        {{-- Usage of the API --}}
    </div>

    <script>
        document.addEventListener('copy-to-clipboard', function(e) {
            console.log(e);
            navigator.clipboard.writeText(e.detail[0].text);
        });
    </script>
</x-filament-panels::page>
