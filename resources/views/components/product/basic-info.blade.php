@props(['product'])
<div id="basicInfo" class="p-4 bg-white rounded-lg shadow-md md:max-w-lg lg:max-w-xl">
    <h2 class="text-2xl font-semibold">Product Information</h2>
    <hr class="my-4">
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
            <label class="block text-sm font-bold text-gray-700">Title</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->title}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Description</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->description}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Category</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->category->name}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Weight</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->weight}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Height</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->height}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Length</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->length}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Width</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->width}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">UPC Code</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->upc_code}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">SKU Code</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->sku_code}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Price (NGN)</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->price_ngn}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Price (CFA)</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->price_cfa}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Color</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->color}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Size</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->size}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Batch</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->batch}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Manufacturer</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->manufacturer}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Brand</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->brand}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Production Date</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->production_date}}</p>
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700">Expiry Date</label>
            <p class="mt-1 text-sm text-gray-500">{{$product->expiry_date}}</p>
        </div>
    </div>
</div>