<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}} | Dashboard</title>
    {{--  --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col md:flex-row">
    {{-- side navigation --}}
    <aside  
        class="sticky top-0 z-10 flex flex-col w-full gap-4 p-4 overflow-y-auto lg:w-1/5 md:w-1/4 md:relative bg-slate-200 md:h-screen md:p-8"
        x-data="{
            show: false
        }"
        x-bind:class="{
            '[&_article]:hidden [&_article]:md:block': !show
        }">
        <div class="flex items-center justify-between">
            <p class="text-xl">DannalisGlobal</p>
            <button x-on:click="show = !show" class="block p-3 md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                  </svg>                  
            </button>
        </div>
        {{-- general --}}
        <article 
            x-cloak class="space-y-2">
            <p class="text-xs font-thin uppercase text-neutral-400 ">general</p>
            <nav class="*:p-2 hover:*:bg-blue-200 hover:*:text-slate-600 text-sm font-semibold text-slate-600 flex flex-col capitalize">
                <a @class(['bg-slate-500 text-white' => request()->routeIs('dashboard.home')]) href="{{route('dashboard.home')}}">Dashboard</a>
                <a @class(['bg-slate-500 text-white' => request()->routeIs('dashboard.product.*')]) href="{{route('dashboard.product.index')}}">Products</a>
                <a @class(['bg-slate-500 text-white' => request()->routeIs('dashboard.order.*')]) href="{{route('dashboard.order.index')}}">Orders</a>
                <a href="#">Stores</a>
            </nav>
        </article>
        {{-- orders --}}
        <article 
            x-cloak class="space-y-2">
            <p class="text-xs font-thin uppercase text-neutral-400 ">orders</p>
            <nav class="*:p-2 hover:*:bg-blue-200 text-sm font-semibold text-slate-600 flex flex-col capitalize">
                <a @class(['bg-slate-500 text-white' => request()->routeIs('dashboard.order.create')]) href="{{route('dashboard.order.create')}}">New order</a>
                <a href="#">Sales</a>
                <a href="#">Restock</a>
                <a href="#">Customers</a>
            </nav>
        </article>
        {{-- account --}}
        <article 
            x-cloak class="space-y-2">
            <p class="text-xs font-thin uppercase text-neutral-400 ">account</p>
            <nav class="*:p-2 hover:*:bg-blue-200 text-sm font-semibold text-slate-600 flex flex-col capitalize">
                <a href="#">messages</a>
                <a href="#">location</a>
                <a href="#">transfer</a>
                <a href="#">settings</a>
            </nav>
        </article>
    </aside>
    {{-- main --}}
    <main class="p-4 overflow-y-auto md:p-6 lg:p-8 grow h-dvh bg-gray-50">
        <section class="min-w-full lg:max-w-md">
            {{$slot}}
        </section>
    </main>
    @stack('scripts')
</body>
</html>