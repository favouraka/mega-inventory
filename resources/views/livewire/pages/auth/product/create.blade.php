<div class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">Create New Product</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a class="text-neutral-400" href="{{route('dashboard.product.index')}}">Products</a>
            <span class="text-slate-300">|</span>
            <a href="{{route('dashboard.product.index')}}">Create New Product</a>
        </nav>
    </header>
    {{-- body --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- basic information --}}
        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-semibold">Basic information</h3>
            <div class="block space-y-2">
                <label for="title" class="font-light">Product name</label>
                <input wire:model='title' type="text" placeholder="Name of the product" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('title')
                    <label for="title_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>
            <div class="block space-y-2">
                <label for="description" class="font-light">Product description</label>
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
                                backgroundImage: `url('{{$item->temporaryUrl()}}')`,
                            }"
                            class="relative bg-center bg-cover rounded-lg size-20">
                            <button wire:click="removeImage({{$key}})" class="absolute p-1 px-2 text-sm text-red-500 border border-red-600 rounded-full bg-white/70 -top-2 -right-2">&times;</button>
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
                    <label for="category" class="font-light">Select category</label>
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
        {{-- end of product images --}}

        

        {{-- shipping information --}}
        <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
            <h3 class="text-sm font-semibold">Shipping information</h3>
            <div class="block space-y-2 lg:w-1/2">
                <label for="weight" class="font-light">Weight (grams)</label>
                <input name="weight" wire:model='weight' type="text" placeholder="Weight in grams" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('weight')
                    <label for="weight_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="width" class="font-light">Width (mm)</label>
                <input name="width" wire:model='width' type="text" placeholder="Width in millimeters" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('width')
                    <label for="width_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
           </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="height" class="font-light">Height (mm)</label>
                <input name="height" wire:model='height' type="text" placeholder="Height in millimeters" class="block w-full p-3 border rounded-lg bg-slate-50">
                @error('height')
                    <label for="height_error" class="text-sm italic text-red-500">{{$message}}</label>
                @enderror
            </div>

            <div class="block space-y-2 lg:w-1/2">
                <label for="length" class="font-light">Length (mm)</label>
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
                <div class="block space-y-2 lg:w-1/2">
                    <label for="upc_code" class="font-light">UPC</label>
                    <input name="upc_code" wire:model='upc_code' type="text" placeholder="Enter UPC code" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('upc_code')
                        <label for="upc_code" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
                <div class="block space-y-2 lg:w-1/2">
                    <label for="sku_code" class="font-light">SKU</label>
                    <input name="sku_code" wire:model='sku_code' type="text" placeholder="Enter SKU code" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('sku_code')
                        <label for="sku_code" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>
            </article>
            
            {{-- pricing information --}}
            <article class="p-4 space-y-4 bg-white rounded-lg shadow-md h-fit">
                <h3 class="text-sm font-semibold">Pricing information</h3>
                <div class="block space-y-2 lg:w-1/2">
                    <label for="price_ngn" class="font-light">Naira price (NGN)</label>
                    <input name="price_ngn" wire:model='price_ngn' type="text" placeholder="Price in Naira" class="block w-full p-3 border rounded-lg bg-slate-50">
                    @error('price_ngn')
                        <label for="price_ngn_error" class="text-sm italic text-red-500">{{$message}}</label>
                    @enderror
                </div>

                <div class="block space-y-2 lg:w-1/2">
                    <label for="price_cfa" class="font-light">CFA price (CFA)</label>
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
