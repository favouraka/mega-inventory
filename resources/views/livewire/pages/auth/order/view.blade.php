<?php

use App\Models\Order;
use function Livewire\Volt\{state, layout, title, mount};

state(['order' => fn (Order $order) => $order])->locked();
layout('layouts.dashboard');
title(fn () => 'View Order: '.$this->order->reference);

?>

<div class="space-y-4">
    <header class="flex flex-col-reverse items-center justify-between gap-4 p-4 rounded-md shadow-sm md:flex-row">
        <h1 class="text-4xl font-extralight">View Order</h1>    

        <nav class="inline-block p-2 px-3 space-x-1 text-sm rounded-lg bg-slate-50">
            <a class="text-neutral-400" href="{{route('dashboard.home')}}">Dashboard</a>
            <span class="text-slate-300">|</span>
            <a class="text-neutral-400" href="{{route('dashboard.product.index')}}">Orders</a>
            <span class="text-slate-300">|</span>
            <span>View Order</span>
        </nav>
    </header>
    {{-- meta data --}}
    <section class="flex gap-4 flex-wrap">
        <div class="max-w-2xl p-4 rounded-md flex-1 shadow-md bg-white">
            <span class="text-xl font-light text-center py-4 block w-full">Order Information</span>
    
            <div class="mt-4 [&>*]:p-2  divide-y">
                <div class="flex justify-between">
                    <div class="font-medium">Customer Name:</div>
                    <div>{{ $this->order->customer_name }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="font-medium">Phone:</div>
                    <div>{{ $this->order->customer_phone }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="font-medium">Email:</div>
                    <div>{{ $this->order->customer_email }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="font-medium">Store Purchased At:</div>
                    <div>{{ $this->order->store->name ?? $this->order->sales()->first()->stock->store->name }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="font-medium">Total:</div>
                    <div>₦ {{ 
                                $this->order->sales
                                    ->sum(function($sale) {
                                            return $sale->quantity * $sale->sale_price;
                                        }) 
                        }}</div>
                </div>
                <div class="flex justify-between">
                    <div class="font-medium">Status:</div>
                    <div>{{ $this->order->status }}</div>
                </div>
            </div>
    
            <span class="text-xl font-light text-center py-4 block w-full">Order Summary</span>
            {{-- items --}}
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Item
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Price
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($this->order->sales as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->stock->product->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->quantity * $item->sale_price }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <div class="max-w-sm rounded-md flex-1">
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <span class="text-base font-semibold">Sales information</span>
            </div>
        </div>
    </section>
    
</div>
