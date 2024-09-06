<x-filament-panels::page>
    <div
        x-data="{
            fileString: null,
            userShare: function(){
                console.log(this.fileString, 'String PDF starters')
                if (navigator.canShare) {
                    fetch('data:application/pdf;base64,'+this.fileString)
                        .then(response => response.blob())
                        .then(blob => {
                            const file = new File([blob], `Dannalis_payment_receipt_${Date.now()}.pdf`, { type: 'application/pdf' });
                            navigator.share({
                                title: 'Dannalis Global Resources',
                                text: `Receipt for ${$wire.order.customer_name}`,
                                files: [file]
                            }).catch(error => console.error('Error sharing:', error));
                        })
                        .catch(error => console.error('Error fetching file:', error));
                } else {
                    console.warn('Web Share API is not supported in this browser.');
                }
            },

        }"
        x-on:share-file.window="($event) => {
                fileString = $event.detail.fileString;
                $dispatch('open-modal', { id: 'share-modal' });
            }"

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
