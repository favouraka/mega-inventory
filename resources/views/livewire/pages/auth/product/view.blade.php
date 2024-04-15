<div class="space-y-4">

    {{-- breadcrumbs --}}
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">Manage Product</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a class="text-neutral-400" href="{{route('dashboard.product.index')}}">Products</a>
            <span class="text-slate-300">|</span>
            <span>Manage Product</span>
        </nav>
    </header>

    {{-- success message --}}
    @if(session()->has('success'))
        <div x-data x-on:hide-notification="$el.classList.replace('flex', 'hidden') " class="flex items-center justify-between p-4 bg-green-100 rounded-lg">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 text-green-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-500">{{session('success')}}</span>
            </div>
            <button x-on:click="$dispatch('hide-notification')" class="text-gray-500 hover:text-gray-700" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="flex flex-wrap gap-4">
        {{-- card component that contains basic product information--}}
         <x-product.basic-info :product="$this->product" />

        <div class="flex flex-col gap-4">
            {{-- card component that shows photos like gallery --}}
           <div x-data="{
                    index: -1, 
                    images: @js($this->product->images),
                }" 
                class="p-4 bg-white rounded-lg shadow-md h-fit lg:max-w-sm md:max-w-xs ">
               <h2 class="text-xl font-bold">Gallery</h2>
               <hr class="my-4">
               <div class="grid grid-cols-3 gap-2">
                   @foreach ($this->product->images as $key => $photo)
                       <div class="flex items-center justify-center bg-gray-200 w-fit h-fit">
                           <img  src="{{ strpos($photo->path, 'http') === 0 ? $photo->path : asset('storage/'.$photo->path) }}" alt="{{$photo->path}}" class="w-auto rounded-lg">
                       </div>
                   @endforeach
               </div>
               {{-- pop up photo viewer --}}
                <div>
                    <div x-cloak x-show="index !== -1" @keydown.escape.window="index = -1" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="relative">
                            <button @click="index = -1 " class="absolute top-0 right-0 m-4 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <template x-if="index !== -1">
                                <img x-bind:src="index !== -1 &&  images[index].path " class="max-w-full max-h-full">
                                <div class="absolute bottom-0 left-0 right-0 flex justify-center gap-2 p-4 bg-white">
                                    <button 
                                        @click="index = (index === 0) ? images.length - 1 : index - 1" 
                                        x-bind:disabled="index === 0"
                                        class="px-4 py-2 text-white bg-blue-500 rounded-md disabled:bg-gray-400">
                                        Previous
                                    </button>
                                    <button 
                                        @click="index = (index === images.length - 1) ? 0 : index + 1" 
                                        x-bind:disabled="index === images.length - 1"
                                        class="px-4 py-2 text-white bg-blue-500 rounded-md disabled:bg-gray-400">
                                        Next
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                {{-- end of photo viewer --}}
           </div>
            
           {{-- inventory products --}}
           <div class="p-4 bg-white rounded-lg shadow-md h-fit lg:max-w-md md:max-w-md">
               <h2 class="text-xl font-bold">Inventory</h2>
               <hr class="my-4">
               <div class="grid grid-flow-row gap-2">
                    {{-- shows stock data in all locations --}}
                    @forelse ($this->stockData as $item)
                        <div 
                            @class([
                                'flex flex-col items-center justify-center p-4 rounded-lg', 
                                'outline outline-blue-200 bg-blue-50 text-blue-500' => (auth()->user()->store == $item->store),
                                'bg-gray-200 text-slate-600' => !(auth()->user()->store == $item->store),
                            ])>
                            <h3 class="text-lg font-semibold">{{$item->store->name}}</h3>
                            <p class="text-gray-500">Quantity: {{$item->quantity}}</p>
                        </div>                    
                    @empty                        
                        <div class="flex flex-col items-center justify-center p-4 text-pink-600 rounded-lg bg-pink-50">
                            <h3 class="text-lg font-semibold">Product not available in any store</h3>
                        </div>
                    @endforelse
                    {{-- check if product exists in user current location --}}
                    @if ( auth()->user()->store->stocks()->whereHas('product', function($query) {
                                $query->where('id', $this->product->id);
                            })->first() )
                            {{-- restock product in location --}}
                            <a href="#"
                                class="px-4 py-2 text-center text-white bg-blue-500 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm1-2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd" />
                                    <path d="M13 3a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-2a1 1 0 011-1zm-1 9a1 1 0 012 0v1a1 1 0 11-2 0v-1z" />
                                </svg>
                                Restock
                            </a>
                        @else 
                        {{-- add to inventory if not available --}}
                            <a href="{{ route('dashboard.stock.add', [ 'product' => $this->product->id]) }}"
                                class="px-4 py-2 text-center text-white bg-green-500 rounded-md hover:bg-gradient-to-b hover:from-green-400 hover:to-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm1-2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd" />
                                    <path d="M13 3a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-2a1 1 0 011-1zm-1 9a1 1 0 012 0v1a1 1 0 11-2 0v-1z" />
                                </svg>
                                Add to Inventory
                            </a>
                    @endif
               </div>
           </div>      
        </div>     
    </div>
    <div 
        class="p-4 bg-white rounded-lg shadow-md h-fit lg:max-w-sm">
        <h2 class="text-xl font-bold">Actions</h2>
        <hr class="my-4">
        <div class="flex justify-between ">
            <a href="{{ route('dashboard.product.edit', $this->product->id) }}" 
                class="px-4 py-2 text-white bg-blue-500 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V5zm1-2a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" clip-rule="evenodd" />
                    <path d="M13 3a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0V7h-1a1 1 0 110-2h1V4a1 1 0 011-1z" />
                </svg>
                Edit
            </a>
            <button class="px-4 py-2 text-white bg-yellow-500 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm0-6a1 1 0 011 1v2a1 1 0 11-2 0v-2a1 1 0 011-1zm0-5a1 1 0 011 1v1a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Hide
            </button>
            <button class="px-4 py-2 text-white bg-red-500 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm0-10a1 1 0 011 1v5a1 1 0 11-2 0V7a1 1 0 011-1zm-1 9a1 1 0 012 0v1a1 1 0 11-2 0v-1z" clip-rule="evenodd" />
                </svg>
                Delete
            </button>
        </div>
    </div>
</div>