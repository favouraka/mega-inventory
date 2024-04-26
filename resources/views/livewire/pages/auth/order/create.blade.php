<section class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">New Order</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <span>New Order</span>
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
    <div class="flex gap-4 flex-col md:flex-row h-min">
        {{-- search and items --}}
        <div class="flex flex-wrap justify-between w-full gap-4 md:w-3/4 lg:w-2/3 md:flex-row h-fit">
            {{-- search console --}}
            <article class="grid w-full grid-flow-row gap-8 p-4 space-y-2 bg-white rounded-lg shadow-sm lg:grid-flow-col">
                <div class="">                
                    <label for="search" class="block text-lg font-light">Search for products</label>
                    <input
                        wire:model.live.debounce.200ms='search'
                        placeholder="Search for products here..."
                        class="w-full p-2 px-3 border rounded-lg border-slate-400 bg-slate-50 placeholder:text-neutral-300 placeholder:text-sm"
                        type="search" 
                        name="search">
                </div>
                <div class="flex gap-4">
                    {{-- cancel button --}}
                    <button 
                        @disabled($this->orderItems->count() < 1)
                        wire:click='clearCart' 
                        class="p-2 px-4 mt-auto font-semibold text-red-600 uppercase bg-pink-100 rounded-lg disabled:bg-gray-400 disabled:text-gray-600"
                        >&times; cancel order</button>
                    {{-- pause order --}}
                    <button 
                        @disabled($this->orderItems->count() < 1)
                        class="p-2 px-4 mt-auto font-semibold text-yellow-600 uppercase rounded-lg bg-amber-100 disabled:bg-gray-400 disabled:text-gray-600"
                        >pause</button>
                </div>
            </article>
            {{-- search results --}}
            @if ($this->search)
                @if ($this->results->count())                    
                    <div wire:model.live='search' class="w-full relative p-4 border border-blue-400 rounded-lg shadow-sm">
                        <p class="pb-4 text-lg font-black text-slate-700">Search results for "{{$this->search}}"</p>
                        <button x-on:click="$dispatch('input', '')" class="underline text-blue-500 text-sm absolute right-4 top-4">clear search</button>
                        <div class="divide-y">
                            @foreach ($this->results as $item)                                
                                <div class="flex gap-4 p-4 bg-gray-100 flex-col md:flex-row">
                                    <div class="flex-1">
                                        <p class="text-lg ">{{$item->product->title}} <span class="font-semibold">[NGN {{$item->product->price_ngn}}]</span></p>
                                        <p class="text-xs uppercase">quantity: {{$item->quantity}}</p>
                                    </div>
                                    <div class="">
                                        @if ($this->orderItems->where('stock_id', $item->id)->isNotEmpty())
                                            <button wire:click="removeFromCart({{$item->id}})" class="p-2 px-4 font-thin text-white bg-red-500 rounded-lg">Remove from order -</button>
                                        @else
                                            <button wire:click="addItem({{$item->id}})" class="p-2 px-4 font-thin text-white bg-blue-500 rounded-lg">Add to order +</button>                                           
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full p-8 text-lg font-semibold text-center bg-gray-200 border-gray-300 rounded-lg">
                        <p>No results for "{{$this->search}}"</p>
                    </div>
                @endif                
            @endif
            {{-- items in new order --}}
            @if ($this->orderItems->count() > 0)
            <div class="flex-1 max-w-2xl">
                <p class="pb-4 text-lg font-black text-slate-700">Items in cart</p>
                <div class="divide-y rounded-md shadow-md overflow-clip border border-green-700">
                    @foreach ($this->orderItems as $sale)
                        <div class="flex relative flex-wrap gap-4 p-4 bg-slate-50 items-end  ">
                            {{-- name  --}}
                            <div class="space-y-1 basis-full">
                                <span class="font-thin text-sm">Product name:</span>
                                <p class="text-lg font-bold">{{$sale->stock->product->title}}</p>
                            </div>
                            {{-- remove from cart --}}
                            <button wire:click="removeFromCart({{$sale->stock_id}})" class="absolute right-2 top-0 float-right p-2 text-red-500 font-semibold text-3xl">&times;</button>

                            {{-- quanity --}}
                            <div class="md:flex-1 space-y-2 flex-shrink">
                                <p class="text-sm flex-grow font-light basis-full">Quantity:</p>
                                <div class="flex max-w-sm gap-2">
                                    <button wire:click="subtractItem({{$sale->stock_id}})" 
                                        @disabled($sale->quantity <= 1)
                                        class="p-2 px-4 font-thin text-amber-700 bg-amber-200 rounded-lg disabled:bg-gray-400 disabled:text-gray-600">-</button>
                                    <input name="quantity-{{$sale->stock_id}}" x-on:change.prevent="($evt) => {
                                        $wire.updateQuantity(@js($sale->stock_id), $evt.target.value)
                                    }" class="text-lg md:w-16 w-8  text-center" value="{{$sale['quantity']}}">
                                    <button wire:click="addItem({{$sale->stock_id}})" class="p-2 px-4 font-thin text-white bg-green-500 rounded-lg">+</button>
                                </div>
                            </div>
                            {{-- price --}}
                            <div class="flex flex-1 flex-wrap flex-grow space-y-2">
                                <p class="text-sm flex-grow font-light basis-full">Price:</p>
                                <div class="bg-blue-50 border border-gray-700 rounded-l-md p-2 px-4">₦</div>
                                <input name="price-{{$sale->stock_id}}" x-on:change.prevent="($evt) => {
                                        $wire.updatePrice(@js($sale->stock_id), $evt.target.value);
                                    }" 
                                    type="number" class="px-2 border w-2/5 md:w-1/2" min="1" 
                                    value="{{$sale->sale_price}}">
                                {{-- reset cart item sale_price to cost_price --}}
                                <button wire:click="resetPrice({{$sale->stock_id}})" 
                                    class="p-2 md:px-4 font-thin border-red-500 border text-red-500 rounded-r-md text-sm flex items-center ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 md:mr-4 mr-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>                                      
                                    <span class="hidden md:block">Reset</span></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        {{-- info --}}
        @if ($this->orderItems->count() > 0)
            <aside class="flex-1 ">
                {{-- price and items --}}
                <form 
                    wire:submit.prevent='createOrder'
                    class="shadow-md bg-amber-50 rounded-md top-4 md:sticky divide-y border-amber-200">
                    {{-- item breakdown --}}
                    <div class="flex gap-4 p-4">
                        <p class="flex-1 font-semibold">Items added</p>
                        <span class="p-4 rounded-full font-semibold flex items-center bg-amber-300 text-amber-800 text-xs h-8">{{$this->orderItems->sum('quantity')}}</span>
                    </div>
                    {{-- total --}}
                    <div class="flex gap-4 p-4">
                        <p class="flex-1 font-semibold">Total</p>
                        <span class="font-semibold text-xl ">₦ {{$this->orderItems->sum(function($item){
                            return $item->quantity * $item->sale_price;
                        })}}</span>
                    </div>
                    {{-- payment type --}}
                    <div class="p-4">
                        <p class="font-semibold">Payment Method</p>
                        <div class="p-4 text-sm">
                            <label class="flex items-center gap-2">
                                <input required wire:model='payment_method' type="radio" name="payment_method" value="cash" class="form-radio text-amber-500">
                                <span class="">Cash</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input required wire:model='payment_method' type="radio" name="payment_method" value="card" class="form-radio ">
                                <span class="">ATM Card / POS</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input required wire:model='payment_method' type="radio" name="payment_method" value="bank_transfer" class="form-radio ">
                                <span class="">Bank Transfer</span>
                            </label>
                        </div>
                    </div>
                    {{-- customer details --}}
                    <div class="p-4 space-y-2">
                        <p class="font-semibold">Customer Details</p>
                        <div class="grid grid-cols-1 gap-4">
                            <label for="customer_name" class="flex flex-col">
                                <span class="text-sm">Customer Name</span>
                                <input required wire:model='customer_name' type="text" name="customer_name" class="p-2 px-3 border rounded-lg border-slate-400 bg-slate-50">
                            </label>
                            <label for="customer_phone" class="flex flex-col">
                                <span class="text-sm">Customer Phone</span>
                                <input required wire:model='customer_phone' type="text" name="customer_phone" class="p-2 px-3 border rounded-lg border-slate-400 bg-slate-50">
                            </label>
                            <label for="customer_email" class="flex flex-col">
                                <span class="text-sm">Customer Email <em>(Optional)</em> </span>
                                <input wire:model='customer_email' type="email" name="customer_email" class="p-2 px-3 border rounded-lg border-slate-400 bg-slate-50">
                            </label>
                        </div>
                    </div>
                    {{-- submit --}}
                    <div class="p-4 space-y-4">
                        <input wire:model='verify' class="text-lg" type="checkbox" name="verify" id="">
                        <label for="verify">
                            I confirm that the details provided are correct
                        </label>
                        <button type="submit" class="w-full p-2 px-4 font-semibold text-white bg-green-500 rounded-lg">Create Order</button>
                    </div>
                </form>
            </aside>  
        @endif
    </div>
    {{-- loading banner for screen --}}
    <div wire:loading wire:target="createOrder" class="fixed inset-0 bg-gray-800/40 backdrop-blur"></div>
</section>
