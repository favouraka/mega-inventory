<section class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">Products</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a href="{{route('dashboard.product.index')}}">Products</a>
        </nav>
    </header>
    <div class="flex flex-col-reverse justify-between gap-4 md:flex-row">
        <article class="w-full p-4 space-y-2 bg-white rounded-lg shadow-sm md:w-1/3">
            <label for="search" class="block text-lg font-light">Search for products</label>
            <input
                wire:model.live.debounce.200ms='search'
                placeholder="Search for products here..."
                class="w-full p-2 px-3 border rounded-lg border-slate-400 bg-slate-50 placeholder:text-neutral-300 placeholder:text-sm"
                type="search" 
                name="search">
        </article>
        <article class="w-auto p-4 space-y-2 bg-white rounded-lg shadow-sm shrink-0 lg:w-1/3">
            <p class="text-lg font-light capitalize">create new product</p>
            <a href="{{route('dashboard.product.create')}}" class="inline-block p-2 px-3 text-sm font-semibold text-white bg-blue-500 rounded shadow-sm shadow-blue-400">Create &plus;</a>
        </article>
    </div>
    {{-- results --}}
    @if(count($this->products) && $this->search)
        <div class="p-4 uppercase">
            <p>results for: "{{$this->search}}" </p>
        </div>
    @endif
    {{-- product grid --}}
    <div class="grid grid-cols-1 gap-4 rounded-lg md:grid-cols-2 lg:grid-cols-3 bg-neutral-50">
        @if ($this->products)
            <div class="relative overflow-auto bg-white rounded-lg shadow-md max-h-72 col-span-full lg:col-span-2">
                <table 
                    class="w-full table-auto">
                    <thead class="text-sm font-semibold border-b bg-blue-50 whitespace-nowrap">
                        <th class="p-2">Name</th>
                        <th class="p-3">
                            Image
                        </th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Total sold</th>
                        <th class="p-2">Total available</th>
                        <th class="p-2">
                            Actions
                        </th>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($this->products as $item)
                            <tr class="divide-x">
                                <th class="relative p-2">
                                    <div class="w-full">
                                        <p class="text-base font-semibold">{{$item->title}}</p> 
                                        <p class="text-xs font-thin">{{$item->sku_code}}</p>
                                    </div>
                                </th>
                                <td class="flex justify-center p-2">
                                    <div 
                                        x-bind:style="{
                                            backgroundImage: `url('{{asset('/storage/'.$item->images->first()?->path)}}')`
                                        }"
                                        class="bg-gray-200 bg-center bg-cover size-8"></div>
                                </td>
                                <td class="p-2 whitespace-nowrap">
                                    <span class="p-2 text-xs font-light tracking-wide bg-green-200 rounded-lg">NGN {{$item->price_ngn}}</span>                            
                                    <span class="p-2 text-xs font-light tracking-wide rounded-lg bg-amber-200">CFA {{$item->price_cfa}}</span>                            
                                </td>
                                <td class="p-2"></td>
                                <td class="p-2"></td>
                                <td class="p-2">
                                    <button class="p-2 text-sm font-thin text-blue-500 rounded-lg bg-neutral-100">Manage</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- navigation --}}
            <div class="col-span-full lg:col-span-2 *:p-2">
                {{$this->products->links()}}
            </div>
        @else
            <div class="h-24 p-8 font-semibold text-center rounded-lg col-span-full text-slate-500">
                <p>No records found.</p>
            </div>
        @endif
    </div>
</section>
