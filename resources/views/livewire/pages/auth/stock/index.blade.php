<section class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-4xl font-extralight">Stock</span>
            {{-- inventory info --}}
            <div class="flex gap-2 p-2 rounded-lg bg-neutral-100 flex-nowrap">
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
            <span>Stock</span>
        </nav>
    </header>
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
    {{-- search for stock in store --}}
    <div class="flex flex-col-reverse justify-between gap-4 md:flex-row">
        <article class="w-full p-4 space-y-2 bg-white rounded-lg shadow-sm md:w-1/3">
            <label for="search" class="block text-lg font-light">Search for stock</label>
            <input
                wire:model.live.debounce.200ms='search'
                placeholder="Search for stock here..."
                class="w-full p-2 px-3 border rounded-lg border-slate-400 bg-slate-50 placeholder:text-neutral-300 placeholder:text-sm"
                type="search" 
                name="search">
        </article>
        {{-- filter stock  --}}
    </div>
    {{-- product grid --}}
    <div class="grid grid-cols-1 gap-4 rounded-lg md:grid-cols-2 lg:grid-cols-3 bg-neutral-50">
        @if ($this->stocks->count() > 0)
            <div class="relative overflow-x-auto bg-white rounded-lg shadow-md col-span-full lg:col-span-full">
                <table 
                    class="w-full table-auto">
                    <thead class="text-sm font-semibold border-b bg-blue-50 whitespace-nowrap">
                        <th class="p-2">Name</th>
                        <th class="p-3">
                            Image
                        </th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Quantity available</th>
                        <th class="p-2">Quantity sold</th>
                        <th class="p-2">
                            Actions
                        </th>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($this->stocks as $item)
                            <tr class="divide-x">
                                <th class="relative p-2 text-left">
                                    <div class="w-full">
                                        <p class="text-base font-semibold">{{$item->product->title}}</p> 
                                    </div>
                                </th>
                                <td class="flex justify-center p-2">
                                    @php
                                        $photo = $item->product->images->first();
                                    @endphp
                                    <div 
                                        x-bind:style="{
                                            backgroundImage: `url('{{strpos($photo->path, 'http') === 0 ? $photo->path : asset('storage/'.$photo->path) }}')`
                                        }"
                                        class="bg-gray-200 bg-center bg-cover size-20"></div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="p-2 text-xs font-light tracking-wide bg-green-200 rounded-lg">NGN {{$item->price_ngn}}</span>                            
                                    <span class="p-2 text-xs font-light tracking-wide rounded-lg bg-amber-200">CFA {{$item->price_cfa}}</span>                            
                                </td>
                                <td class="p-2">
                                    {{$item->quantity}}
                                </td>
                                <td class="p-2">
                                    {{$item->sales->sum('quantity')}}
                                </td>
                                <td class="p-2 space-y-2">
                                    <a href="{{ route('dashboard.product.view', ['product' => $item->product->id]) }}" 
                                       class="inline-block p-2 text-sm font-semibold text-white bg-blue-500 rounded-lg whitespace-nowrap">View Product</a>
                                    <a href="#"
                                        class="p-2 text-sm font-semibold text-blue-500 border border-blue-500 rounded-lg">Restock</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- navigation --}}
            <div class="col-span-full">
                {{$this->stocks->links()}}
            </div>
        @else
            <div class="h-24 p-8 font-semibold text-center rounded-lg col-span-full text-slate-500">
                <p>No records found.</p>
            </div>
        @endif
    </div>
</section>
