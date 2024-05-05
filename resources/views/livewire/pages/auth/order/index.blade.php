<?php
use App\Models\Order;
use Carbon\Carbon;
use function Livewire\Volt\{state, layout, title, computed, usesPagination};

usesPagination();

state(['search' => ''])->url();

state(['page' => 1])->url(except: 1);

state(['start_date', 'end_date'])->url();

layout('layouts.dashboard');

title('Orders');

$orders = computed(function() {
    return Order::has('sales')->whereHas('sales', function($query) {
                $query->whereHas('stock', function($q){
                    $q->whereStoreId(auth()->user()->store->id);
                });
            })->when($this->search, function($query, $search){
                $query->where('customer_name', 'like', '%'.$search.'%' )
                    ->orWhere('reference', $search);
            })->when($this->start_date, function($query, $start){
                $query->where('created_at', '>=', $start);
            })->when($this->end_date, function($query, $end){
                $query->where('created_at','<=', $this->end_date);
            })->paginate(20);
    });
?>

<div class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">Orders</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <span>Orders</span>
        </nav>
    </header>
    <article class="p-4 bg-white rounded-md flex flex-wrap border shadow-md gap-4">
        <div class="basis-full md:basis-1/2 ">
            <label for="search" class="ext-xl font-thin">Search for invoice</label>
            <input class="p-3 px-6 border rounded-md block max-w-lg" wire:model.live="search" type="search" name="start_date" placeholder="Search for Reference, Customer Name">
        </div>
        {{--  --}}
        <div class="[&_input]:p-3 [&_input]:border [&_input]:rounded-md">
            <span class="block">Filter by dates</span>
            <div class="inline-block mr-8">
                <label for="start_date">From</label>
                <input type="date" wire:model.live="start_date" name="start_date" max="{{$start_date ?? Carbon::now()->format('YYYY-MM-DD')}}"> 
            </div>

            <div class="inline-block">
                <label for="end_date">To</label>
                <input type="date" wire:model.live="end_date" name="end_date" max="{{now()}}"> 
            </div>
        </div>
    </article>

    {{--  --}}
    <table class="p-4 border shadow-lg rounded-md table-full max-w-4xl overflow-auto">
        <thead class="[&_th]:p-2 [&_th]:px-4 [&_th]:bg-slate-700 [&_th]:text-white text-sm">
            <tr>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Customer Phone</th>
                <th>Products Sold</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="[&_td]:p-4  [th_a]:inline-block divide-y">
            @foreach ($this->orders as $order)
                <tr class="divide-x">
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>{{ $order->customer_phone }}</td>
                    <td>{{ $order->sales->count() }}</td>
                    <td>{{ $order->sales->sum(fn ($i) => $i->quantity * $i->sale_price ) }}</td>
                    <td>
                        <a class="py-2 px-4 bg-blue-500 font-semibold rounded-md text-sm text-white" href="{{route('dashboard.order.view', ['order' => $order->id ])}}">View Order</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$this->orders->links()}}
</div>
