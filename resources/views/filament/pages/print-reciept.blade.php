<x-filament-panels::page>
    <div 
        x-ignore
        ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('pdf-component') }}"
        x-data="pdfComponent({
            state: null,
        })"
        x-on:share-file.window="shareFile"
        x-on:print-file.window="printFile"
        x-on:download-file.window="downloadFile"
        class="border rounded-md p-4 max-w-lg gap-8 flex flex-col" 
        x-ref="documentArea">
            <p class="text-lg font-light text-success-500">Payment Reciept</p>
            {{$this->infolist}}
    </div>
</x-filament-panels::page>
