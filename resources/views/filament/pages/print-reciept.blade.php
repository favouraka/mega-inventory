<x-filament-panels::page>
    <div 
        x-ignore
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('pdf-component') }}"
        x-data="pdfComponent({
            state: @js($this->order->customer_name),
            isLoading: true,
        })"
        x-on:share-file.window="shareFile"
        x-on:download-file.window="downloadFile"
        class="flex flex-col max-w-lg gap-8 p-4 border rounded-md" 
        x-ref="documentArea">
            <p class="text-lg font-light text-success-500">Payment Reciept </p>
            {{$this->infolist}}
            {{-- page loading spinner --}}
            <x-filament::modal 
                id="share-modal"
                icon="heroicon-o-share">
                <x-slot name="heading">
                    Share reciept
                </x-slot>

                <x-slot name="description">
                    Share via WhatsApp, Email, Telegram etc
                </x-slot>

                <x-filament::button x-on:click="userShare" color="primary">
                    Share Reciept
                </x-filament::button>
            
                {{-- Modal content --}}
            </x-filament::modal>
    </div>
</x-filament-panels::page>
