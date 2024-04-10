<section class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <div class="flex items-center gap-4">
            <span class="text-4xl font-extralight">Add Stock</span>
            {{-- inventory info --}}
            <div class="flex gap-2 p-2 rounded-lg bg-neutral-100">
                {{-- svg location, map pin icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>                  
                {{-- location name in a span element --}}
                <span>{{auth()->user()->store->name}}</span>
            </div>
        </div>

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{ route('dashboard.home') }}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a class="text-neutral-400" href="{{ route('dashboard.product.view', ['product' => $this->product->id]) }}">View Product</a>
            <span class="text-slate-300">|</span>
            <span>Add Stock</span>
        </nav>
    </header>

    {{-- html form for adding stock to store location --}}
    <form class="p-4 space-y-4 bg-white rounded-md shadow-sm" wire:submit.prevent="addToStore">
        <div class="max-w-xl rounded-md border-yellow-600bg-yelow-100">
            <div class="flex items-center gap-4 p-2 bg-yellow-100">
                {{-- svg icon for warning --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 3a9 9 0 1 1 13.856 0M12 9v2m0 4h.01" />
                </svg>
                <span class="text-yellow-600">Please make sure to add the correct product and quantity.</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <label for="quantity" class="text-lg font-medium">Product:</label>
            <span class="">{{$this->product->title}}</span>
        </div>
        <div class="flex items-center gap-4">
            <label for="quantity" class="text-lg font-medium">Quantity:</label>
            <input required type="number" id="quantity" name="quantity" min="1" wire:model="quantity" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        
        @if($errors->first())
            <div class="p-2 text-red-600 bg-red-100 rounded-md">
                {{ $errors->first() }}
            </div>
        @endif
        
        <button type="submit" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded-md hover:bg-blue-600">Add to Store</button>
    </form>
    
    
</section>
