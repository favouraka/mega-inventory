<div class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">Edit Product</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a class="text-neutral-400" href="{{route('dashboard.product.index')}}">Products</a>
            <span class="text-slate-300">|</span>
            <span>Edit Product</span>
        </nav>
    </header>
    {{-- body --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- basic information --}}
        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-semibold">Basic information</h3>
            <div class="block space-y-2">
                <label for="title" class="font-light">Product name <span class="text-red-500">*</span></label>
                <input wire:model='title' type="text" placeholder="Name of the product" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('title')
                    <label for="title_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="block space-y-2">
                <label for="description" class="font-light">Product description <span class="text-red-500">*</span></label>
                <textarea wire:model='description' placeholder="Product description here" class="block w-full p-3 border rounded-lg bg-slate-50" name="" id="" cols="30" rows="4"></textarea>
                @error('description')
                    <label for="descr_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
        </article>
        {{-- product images --}}
        <div class="space-y-4">
            <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
                <h3 class="text-sm font-semibold">Product images</h3>
                <div
                    x-data="{
                        open_upload: false,
                    }" 
                    class="relative flex gap-2">
                    {{-- rows of images --}}
                    @foreach ($this->images as $key => $item)
                        <div 
                            x-bind:style="{
                                backgroundImage: `url('{{ strpos($item->path, 'http') === 0 ? $item->path : asset('storage/'.$item->path)}}')`,
                            }"
                            class="relative bg-center bg-cover rounded-lg size-20">
                            <button x-on:click="() => {
                                confirm('Are you sure you want to delete this image?\nThis action cannot be reversed')  &&
                                $wire.removeImage({{$key}});
                                }" 
                                class="absolute p-1 px-2 text-sm text-red-500 border border-red-600 rounded-full bg-white/70 -top-2 -right-2">&times;</button>
                        </div>
                    @endforeach
                    <div 
                        x-on:photo-uploaded.window="open_upload = false"
                        x-on:click="open_upload = true" class="border-2 border-blue-500 border-dotted rounded-lg size-20">
                        <p class="text-xs font-semibold text-center text-blue-500"><span class="text-4xl font-black">&plus;</span><br>Add image</p>
                    </div>
                    {{-- popup --}}
                    <div 
                        x-on:click.away="() => {
                            open_upload = false;
                            $wire.set('upload_image', null);
                        }" 
                        x-show="open_upload"
                        x-cloak
                        class="absolute flex flex-col p-4 space-y-2 bg-white border rounded-lg shadow-xl -top-0 ">
                        @if (!$this->upload_image)                            
                            <label for="upload_image" class="block text-sm font-bold">Upload image</label>
                            <input wire:model='upload_image'
                                    accept="image/*"
                                    class="file:bg-blue-50 file:text-blue-600 file:border file:border-blue-500 file:p-2 file:rounded-lg" 
                                    type="file" name="upload_image" id="upload_image">
                            <label for="upload_image" class="block text-sm text-gray-500">*Only supports image file types</label>
                            @error('upload_image')
                                <label for="upload_image" class="block italic text-red-500">{{$message}}</label>
                            @enderror
                        @else
                            <label for="upload_image" class="block text-sm font-bold">Image preview</label>
                            <img class="mx-auto size-40" src="{{$this->upload_image->temporaryUrl()}}" alt="upload_image">
                            <button wire:click='uploadImage' class="inline-block p-2 px-3 text-sm font-semibold text-center text-white bg-blue-500 rounded-lg w-100">Upload</button>
                            <button wire:click='$set("upload_image", null)' class="inline-block p-2 px-3 font-semibold text-blue-500 border border-blue-500 rounded-lg">Cancel</button>
                        @endif
                    </div>
                    {{-- end of popup --}}
                </div>
                @error('images')
                    <label for="images" class="block italic text-red-500">{{$message}}</label>
                @enderror
            </article>
            <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
                <h3 class="text-sm font-semibold">Category</h3>
                <div class="block space-y-2">
                    <label for="category" class="font-light">Select category <span class="text-red-500">*</span></label>
                    <select wire:model='category_id' name="category" id=""  class="block w-full p-3 border rounded-lg bg-slate-50">
                        <option value="">-- No category selected --</option>
                        <option value="0">Uncategorized</option>
                        @foreach(App\Models\Category::latest()->get() as $item)
                            <option value="{{$item->id}}"> 
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <label for="category_id_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
            </article>
        </div>
        {{-- end of product & category images --}}

        
        {{-- manufacturer data --}}
        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
            <h3 class="text-sm font-semibold">Product metadata</h3>
            <div class="block space-y-2">
                <label for="manufacturer" class="font-light">Manufacturer <span class="text-red-500">*</span></label>
                <input wire:model='manufacturer' type="text" placeholder="Name of manufacturer" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('manufacturer')
                    <label for="manufacturer_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="flex gap-4">
                <div class="block space-y-2">
                    <label for="production_date" class="font-light">Production date <span class="text-red-500">*</span></label>
                    <input wire:model='production_date' type="date" placeholder="Date of production" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('production_date')
                        <label for="production_date_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
                <div class="block space-y-2">
                    <label for="expiry_date" class="font-light">Expiry date</label>
                    <input wire:model='expiry_date' type="date" placeholder="Date of production" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('expiry_date')
                        <label for="expiry_date_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
            </div>
            
            <div class="block space-y-2">
                <label for="batch" class="font-light">Batch <span class="text-red-500">*</span></label>
                <input name="batch" wire:model='batch' type="text" placeholder="Batch" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('batch')
                    <label for="batch_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="block space-y-2">
                <label for="model" class="font-light">Model <span class="text-red-500">*</span></label>
                <input name="model" wire:model='model' type="text" placeholder="Model" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('model')
                    <label for="model_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
        </article>

        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
            <h3 class="text-sm font-semibold">Stock Keeping information</h3>
            <div class="block space-y-2">
                <label for="color" class="font-light">Color <span class="text-red-500">*</span></label>
                <input name="color" wire:model='color' type="text" placeholder="Color" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('color')
                    <label for="color_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="block space-y-2">
                <label for="size" class="font-light">Size <span class="text-red-500">*</span></label>
                <input name="size" wire:model='size' type="text" placeholder="Size" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('size')
                    <label for="size_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="block space-y-2">
                <label for="brand" class="font-light">Brand <span class="text-red-500">*</span></label>
                <input name="brand" wire:model='brand' type="text" placeholder="Brand" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('brand')
                    <label for="brand_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
        </article>
        {{-- stock keeping information samle card --}}

        {{-- shipping information --}}
        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
            <h3 class="text-sm font-semibold">Shipping information</h3>
            <div class="block space-y-2 lg:w-1/2">
                <label for="weight" class="font-light">Weight (grams) <span class="text-red-500">*</span></label>
                <input name="weight" wire:model='weight' type="text" placeholder="Weight in grams" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('weight')
                    <label for="weight_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="width" class="font-light">Width (mm) <span class="text-red-500">*</span></label>
                <input name="width" wire:model='width' type="text" placeholder="Width in millimeters" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('width')
                    <label for="width_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
           </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="height" class="font-light">Height (mm) <span class="text-red-500">*</span></label>
                <input name="height" wire:model='height' type="text" placeholder="Height in millimeters" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('height')
                    <label for="height_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="length" class="font-light">Length (mm) <span class="text-red-500">*</span></label>
                <input name="length" wire:model='length' type="text" placeholder="Length in millimeters" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('length')
                    <label for="length_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>            
        </article>

        <div class="mb-4 space-y-4 lg:mb-0">
            {{-- stock taking information --}}
            <article class="p-4 space-y-4 bg-white rounded-md shadow-md h-fit">
                <h3 class="text-sm font-semibold">Inventory data</h3>
                <x-scan-barcode/>
                <div class="block space-y-2 lg:w-1/2">
                    <label for="sku_code" class="font-light">SKU <span class="text-red-500">*</span></label>
                    <input name="sku_code" wire:model='sku_code' type="text" placeholder="Enter SKU code" class="block w-full p-3 border rounded-lg bg-slate-50">
                    <button type="button" wire:click="generateSku" class="flex items-center px-4 py-2 text-green-500 border border-green-500 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 0 0-1 1v2.586l-3.707-3.707a1 1 0 1 0-1.414 1.414L8.586 9H6a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2h-2.586l3.707-3.707a1 1 0 1 0-1.414-1.414L11 6.586V4a1 1 0 0 0-1-1z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M10 17a1 1 0 0 1-1-1v-2.586l-3.707 3.707a1 1 0 1 1-1.414-1.414L8.586 11H6a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2h-2.586l3.707 3.707a1 1 0 1 1-1.414 1.414L11 14.586V16a1 1 0 0 1-1 1z" clip-rule="evenodd" />
                        </svg>
                        Generate SKU
                    </button>
                    @error('sku_code')
                        <label for="sku_code" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
            </article>
            
            {{-- pricing information --}}
            <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
                <h3 class="text-sm font-semibold">Pricing information</h3>
                <div class="block space-y-2 lg:w-1/2">
                    <label for="price_ngn" class="font-light">Naira price (NGN) <span class="text-red-500">*</span></label>
                    <input name="price_ngn" wire:model='price_ngn' type="text" placeholder="Price in Naira" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('price_ngn')
                        <label for="price_ngn_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>

                <div class="block space-y-2 lg:w-1/2">
                    <label for="price_cfa" class="font-light">CFA price (CFA) <span class="text-red-500">*</span></label>
                    <input name="price_cfa" wire:model='price_cfa' type="text" placeholder="Price in CFA" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('price_cfa')
                        <label for="price_cfa_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>         
            </article>

            

            <div class="space-x-2">
                <button 
                    wire:click='save' 
                    wire:target='save' 
                    wire:loading.class.remove='bg-blue-500' wire:loading.class.add='bg-blue-200' 
                    class="p-2 px-3 text-lg font-semibold text-white bg-blue-500 rounded-lg"> 
                        <span wire:loading.remove wire:target='save'>Save</span> 
                        <span wire:loading wire:target='save'>Saving...</span> 
                    </button>
                <a href="{{route('dashboard.product.index')}}" class="inline-block p-2 px-3 text-lg text-blue-500 border border-blue-500 rounded-lg">Cancel</a>
            </div>

            @if (session()->has('success'))
                <div class="p-4 text-green-500 bg-green-100 border border-green-500 rounded-lg">
                    <p class="text-center">Product saved successfully!</p>
                </div>
            @endif
        </div>
    </div>
</div>