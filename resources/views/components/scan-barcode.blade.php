<div  
    x-data="{ scan_barcode: false }" 
    class="block space-y-2 lg:w-1/2">
    <label for="upc_code" class="font-light">UPC <span class="text-red-500">*</span></label>
    <input name="upc_code" wire:model='upc_code' type="text" placeholder="Enter UPC code" class="block w-full p-3 border rounded-lg bg-slate-50">
    <button type="button" @click.prevent="scan_barcode = true" class="flex items-center justify-center w-full h-full px-4 py-2 text-white bg-gray-500 rounded-lg">
        Scan Barcode
    </button>

    <div     
        x-show="scan_barcode" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div
            x-on:click.away="scan_barcode = false" 
            class="flex flex-col items-center w-full max-w-md gap-6 p-4 bg-white rounded-lg">
            {{-- Barcode scanning interface --}}
            <h3 class="text-2xl font-semibold">Scan Barcode</h3>

            <div
                x-init="
                    $watch('scan_barcode', function(value){
                        if(value){
                            Quagga.init({
                                inputStream : {
                                    name : 'Live',
                                    type : 'LiveStream',
                                    target: document.querySelector('#barcodeScanner'),    // Or '#yourElement' (optional)
                                    constraints: {
                                        width: 640,
                                        height: 480,
                                    }
                                },
                                decoder : {
                                    readers : [
                                        'code_128_reader',
                                        'ean_reader',
                                        'ean_8_reader',
                                        'code_39_reader',
                                        'code_39_vin_reader',
                                        'codabar_reader',
                                        'upc_reader',
                                        'upc_e_reader',
                                        'i2of5_reader',
                                        '2of5_reader',
                                        'code_93_reader'
                                    ],
                                    multiple: false,
                                }
                                }, function(err) {
                                    if (err) {
                                        console.log(err);
                                        return
                                    }
                                    console.log('Initialization finished. Ready to start');
                                    Quagga.start();
                                });
                                Quagga.onProcessed( data => {
                                    console.log(data);
                                });
                                Quagga.onDetected(function(result) {
                                    console.log(result);
                                    if(result.codeResult) {
                                        console.log('result', result.codeResult.code);
                                        $refs.result.value = result.codeResult.code;
                                        {{-- scan_barcode = false; --}}
                                    } else {
                                        console.log('not detected');
                                    }
                                });
                        } else {
                            Quagga.stop();
                        }
                    })
                "
                x-data="{
                    Quagga: Quagga,
                }"
                class="block space-y-2 h-60 lg:w-1/2">
                <div  id="barcodeScanner"></div>
                <button type="button" @click.prevent="captureImage" class="flex items-center justify-center w-full h-full px-4 py-2 text-white bg-green-500 rounded-lg">
                    Capture Image
                </button>
                <hr class="py-2">
                <label for="result" class="font-light">Result</label>
                <input x-ref="result" type="text" placeholder="Barcode result" class="block w-full p-3 border rounded-lg bg-slate-50">
                {{-- hidden file input --}}
                <input x-on:change="captured = true" id="imageInput" x-ref="file_input" name="image" type="file" accept="image/*" hidden>
            </div>     
        </div>
    </div>
        
    
    @error('upc_code')
        <label for="upc_code" class="text-sm italic text-red-500">{{$message}}</label>
    @enderror
</div>