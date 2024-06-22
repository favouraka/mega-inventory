
<section class="">
    
    <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 divide-x">
        <div class="flex flex-col col-span-2 gap-4 p-4">
            {{-- search console --}}
            <article class="flex flex-col gap-2">
                <div class="space-y-4 py-4">             
                    <label for="search" class="block font-light">Find products</label>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            wire:model.live.debounce.200ms='search'
                            placeholder="Search for products here..."
                            class="w-full border rounded-lg"
                            type="search" 
                            name="search"/>
                        <x-slot name="suffix">
                            <x-filament::icon-button 
                                wire:click="$set('search','')"
                                icon="heroicon-o-x-circle" 
                                color="gray"/>
                        </x-slot>
                    </x-filament::input.wrapper>
                </div>
            </article>
            {{-- search results --}}
            @if ($this->search)
                @if ($this->results->count())                    
                    <div wire:model.live='search' class="relative w-full p-4 border border-gray-50 rounded-lg">
                        <div class="flex justify-between">
                            <p class="pb-2 text-xs font-light">Search results for "{{$this->search}}"</p>
                            <x-filament::icon-button 
                                wire:click="$set('search','')"
                                icon="heroicon-o-x-mark" 
                                color="gray"/>
                        </div>
                        <div class="divide-y">
                            @foreach ($this->results as $item)                                
                                <div class="flex flex-col gap-4 p-4 md:flex-row">
                                    @php
                                        $existingInventory = $item->inventories()->whereStoreId(auth()->user()->store_id)->first();
                                    @endphp
                                    <div class="flex-1">
                                        <p class="text-lg">{{$item->title}} </p>
                                        <span class="block font-semibold">[NGN {{$item->price_ngn}}]</span>
                                        @if ($existingInventory)                                            
                                            <p class="text-base text-gray-500 uppercase">
                                                Quantity: {{ $existingInventory->quantity }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="">
                                        @if ($existingInventory)
                                            {{-- inventory alrady exists --}}
                                            @if ($this->orderItems->where('inventory_id', $existingInventory?->id)->count())
                                                <x-filament::button 
                                                    wire:click="removeFromCart({{$existingInventory->id}})"
                                                    icon="heroicon-o-minus" 
                                                    outlined
                                                    color="danger">
                                                    Remove
                                                </x-filament::button>
                                            @else
                                                <x-filament::button 
                                                    wire:click="addItem({{$existingInventory->id}})"
                                                    icon="heroicon-o-plus" 
                                                    color="success">
                                                    Add
                                                </x-filament::button>
                                            @endif
                                        @else
                                            <x-filament::button 
                                                disabled
                                                icon="heroicon-o-no-symbol" 
                                                color="gray">
                                                Not available
                                            </x-filament::button>                                          
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full p-4 text-lg font-semibold text-center bg-gray-200 border-gray-300 rounded-lg">
                        <p>No results for "{{$this->search}}"</p>
                    </div>
                @endif                
            @endif
            {{-- items in new order --}}
            @if ($this->orderItems->count() > 0)
            <div class="border-t">
                <p class="py-4 pb-2 text-xs  text-gray-500 font-light">Items in cart</p>
                <div class="border border-green-700 divide-y rounded-md overflow-clip shadow-lg ">
                    @foreach ($this->orderItems as $sale)
                        <div class="flex flex-wrap gap-4 p-4">
                            {{-- name  --}}
                            <div class="flex-grow flex-1">
                                <span class="flex-grow text-xs font-thin">Product name:</span>
                                <p class="text-lg font-bold">{{$sale->inventory->product->title}}</p>
                            </div>
                                    
                            {{-- article fields --}}
                            <div class="">                                
                                <span class="text-xs font-thin">Quantity:</span>
                                <div class="flex items-center gap-2">
                                    <x-filament::icon-button 
                                        icon="heroicon-o-minus"
                                        color="danger"
                                        :disabled="$sale->quantity <= 1"
                                        wire:click="subtractItem({{$sale->inventory_id}})" />
    
                                    <span 
                                        x-data="{
                                            acceptInput: function($evt){
                                                let input = $evt.target.innerText;
                                                {{-- content should be type integer --}}
                                                if(Number.isInteger(parseInt(input))){
                                                    {{-- number is an integer --}}
                                                } else {
                                                    $evt.target.innerText = 1;
                                                    input = 1;
                                                }
                                                $wire.updateQuantity(@js($sale->inventory_id), input);
                                            }
                                        }"
                                        name="quantity-{{$sale->inventory_id}}" 
                                        @class([
                                            'p-2 px-4 rounded-md', 
                                            'text-red border border-red' => $errors->has('inventory_quantity_'.$sale->inventory_id)
                                            ])
                                        contenteditable
                                        x-on:keydown.enter="(evt) => {
                                            evt.target.blur();
                                        }"
                                        x-on:blur.prevent="acceptInput" 
                                        class="" x-text="@js($sale['quantity'])"></span>    
                                    <x-filament::icon-button 
                                        icon="heroicon-o-plus"
                                        color="success"
                                        :disabled="$sale->quantity ==  $sale->inventory->quantity"
                                        wire:click="addItem({{$sale->inventory_id}})" />
                                </div>
                            </div>
                            {{-- price --}}
                            <div class="">
                                {{-- <p class="block text-sm">Price:</p> --}}
                                <x-filament::input.wrapper>
                                    <x-slot name="prefix">
                                        ₦
                                    </x-slot>
                                
                                    <x-filament::input
                                        type="number"
                                        value="{{$sale->sale_price}}"
                                        disabled 
                                        {{-- x-on:change.prevent="($evt) => {
                                            $wire.updatePrice(@js($sale->inventory_id), $evt.target.value);
                                        }"  --}}
                                    />
                                
                                    <x-slot name="suffix">
                                        <x-filament::icon-button
                                            icon="heroicon-o-arrow-path"
                                            color="gray" 
                                            wire:click='resetPrice({{$sale->inventory->id}})'/>
                                    </x-slot>
                                </x-filament::input.wrapper>
                            </div>

                            <x-filament::link 
                                color="danger" 
                                wire:click="removeFromCart({{$sale->inventory_id}})"
                                icon="heroicon-o-trash">
                                    Remove
                            </x-filament::link>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="flex flex-col gap-4 p-4 bg-primary">
            {{-- info --}}
            @if ($this->orderItems->count() > 0)
                <aside class="flex-1 shrink-0 min-w-max">
                        <div class="space-y-4 p-4">
                            <p class="flex-1 font-light text-gray-500 text-sm">Order breakdown</p>
                            <div class="flex gap-4">
                                <span>Total</span> 
                                <x-filament::badge color="info">
                                    {{$this->orderItems->sum('quantity')}} items
                                </x-filament::badge>
                            </div>
                            <span style="font-size: 1.75rem" class=" font-semibold ">₦ {{$this->orderItems->sum(function($item){
                                return $item->quantity * $item->sale_price;
                            })}}</span>
                        </div>
                        {{-- payment type --}}
                        <div class="p-4 space-y-4">
                            <p class="text-xs text-gray-500">Payment Method</p>
                            <div class="text-sm">
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
                        <div class="flex items-end p-4">
                            {{$this->createAction}}
                        </div>
                </aside>  
            @endif
        </div>
    </div>
    <x-filament-actions::modals/>
    {{-- loading banner for screen --}}
    <div wire:loading wire:target="createOrder" class="fixed inset-0 bg-gray-800/40 backdrop-blur"></div>
</section>
