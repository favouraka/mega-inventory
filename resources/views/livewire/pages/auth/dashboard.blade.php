<div 
    x-data="{
        logout: false,
    }"
    class="grid lg:grid-cols-3 grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 w-full [&_span]:text-slate-400">
    {{-- label --}}
    <section class="flex items-center justify-between w-full col-span-full">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-4xl font-extralight">Dashboard</span>
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
        {{-- settings --}}
        <div>
            <button title="Dashboard settings" class="p-3 rounded-lg hover bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>              
            </button>
            <button x-on:click="logout = true" class="p-3 rounded-lg bg-rose-50 stroke-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                </svg>                  
            </button>
        </div>
    </section>
    {{-- inventory breakdown --}}
        <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-fuchsia-500 hover:shadow-xl min-h-40">
            <div class="">
                <p class="text-sm tracking-wide text-gray-500 uppercase">total stock</p>
                <h1 class="text-6xl">{{ $this->totalStock }}</h1>
            </div>
            <a href="{{ route('dashboard.stock.index') }}" class="inline-block p-2 text-sm font-semibold uppercase rounded-md text-fuchsia-500 bg-fuchsia-50">manage stock</a>
        </article>
        <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-teal-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
            <div class="">
                <p class="text-sm tracking-wide text-gray-500 uppercase">total products sold (past 7 days)</p>
                <h1 class="text-6xl">{{  "--" }}</h1>
            </div>
            <a href="#" class="inline-block p-2 text-sm font-semibold text-teal-500 uppercase rounded-md bg-teal-50">manage sales</a>
        </article>
        <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-emerald-500 hover:shadow-xl min-h-40">
            <div class="">
                <p class="text-sm tracking-wide text-gray-500 uppercase">total staff</p>
                <h1 class="text-6xl">{{ "--" }}</h1>
            </div>
            <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md text-emerald-500 bg-emerald-50">manage staff</a>
        </article>
    
    {{--  --}}
    {{-- general links --}}
    <span class="pt-4 text-sm font-thin tracking-widest uppercase col-span-full">general</span>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-blue-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">total products</p>
            <h1 class="text-6xl">{{App\Models\Product::count() ?? "--"}}</h1>
        </div>
        <a href="{{route('dashboard.product.index')}}" class="inline-block p-2 text-sm font-semibold text-blue-500 uppercase rounded-md bg-slate-50">manage products</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-amber-500 hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">total invoices</p>
            <h1 class="text-6xl">{{
            // App\Models\Invoice::count() ?? 
            "--"}}</h1>
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md bg-amber-50 text-amber-500">manage invoices</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-green-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">total stores</p>
            <h1 class="text-6xl"> {{
            // App\Models\Store::count() ??
            "--"}} </h1>
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold text-green-500 uppercase rounded-md bg-green-50">manage stores</a>
    </article>

    {{-- orders links --}}
    <span class="pt-4 text-sm font-thin tracking-widest uppercase col-span-full">orders</span>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-indigo-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">new order</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
              </svg>              
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold text-indigo-500 uppercase rounded-md bg-indigo-50">place new order</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-cyan-500 hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">sales</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-cyan-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
              </svg>                          
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md bg-cyan-50 text-cyan-500">manage sales</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-rose-500 hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">restock</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-rose-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
              </svg>                                        
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md bg-rose-50 text-rose-500">manage restock</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-fuchsia-500 hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">restock</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-fuchsia-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>                                                      
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md bg-fuchsia-50 text-fuchsia-500">manage restock</a>
    </article>
    {{-- account links --}}
    <span class="pt-4 text-sm font-thin tracking-widest uppercase col-span-full">account</span>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-yellow-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">messages</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-yellow-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
              </svg>                            
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold text-yellow-500 uppercase rounded-md bg-yellow-50">view messages</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-red-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">locations</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
              </svg>
                                          
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold text-red-500 uppercase rounded-md bg-red-50">view locations</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 border-orange-500 rounded-lg shadow-md hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">transfer</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-orange-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
              </svg>                                                                    
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold text-orange-500 uppercase rounded-md bg-orange-50">manage transfers</a>
    </article>
    <article class="p-4 space-y-2 bg-white border-0 border-l-8 rounded-lg shadow-md border-sky-500 hover:shadow-xl min-h-40">
        <div class="">
            <p class="text-sm tracking-wide text-gray-500 uppercase">settings</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.75" stroke="currentColor" class="size-[3.75rem] stroke-sky-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>                                                                                  
        </div>
        <a href="#" class="inline-block p-2 text-sm font-semibold uppercase rounded-md bg-sky-50 text-sky-500">manage settings</a>
    </article>
    {{-- end of grid --}}
    {{-- logout form --}}
    <main 
        x-show="logout" 
        x-cloak 
        class="inset-0 bg-gray-500/75 fixed backdrop-blur flex items-center justify-center [&_button]:rounded-lg p-4">
            <article
                x-show="logout" 
                x-transition:enter="transition-all ease-in  duration-300"
                x-transition:enter-start="scale-70 opacity-80"
                x-transition:enter-end="scale-100 opacity-100"
                x-on:click.outside="logout = false" class="w-full p-6 bg-white rounded shadow-sm lg:w-1/6 md:w-1/3">
                <h2 class="pb-4 text-3xl">Logout</h2>
                <p class="text-base font-thin">Are you sure you want to logout</p>
                <hr class="my-4">
                <button wire:click="logout" class="p-2 text-sm font-semibold text-white bg-red-500">Logout</button>
                <button x-on:click="logout = false" class="p-2 text-sm font-semibold bg-slate-50 text-slate-500">Cancel</button>
            </article>
    </main>
</div>
