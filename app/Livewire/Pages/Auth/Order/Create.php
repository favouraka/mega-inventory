<?php

namespace App\Livewire\Pages\Auth\Order;

use App\Facades\Cart;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\{Title, Computed};
use App\Traits\WithCart;

#[Title('New Order')]
class Create extends Component
{

    use WithCart;

    public $search = '';
    public $payment_method = 'cash';
    public $customer_phone;
    public $customer_name;
    public $customer_email;
    public $reference;


    protected $queryString = [
                'search' => ['except' => '']
            ];

    public function getResultsProperty()
    {
        return auth()->user()->store->stocks()->when($this->search, function($query, $search){
            $query->whereHas('product', function($q) use ($search){
                $q->where('title','like','%'.$search.'%')
                    ->orWhere('upc_code','like','%'.$search.'%')
                    ->orWhere('sku_code','like','%'.$search.'%')
                    ;
            });
        })->latest()->take(6)->get();
    }

    public function mount()
    {
        $this->reference = 'ORD-' . substr(md5(time()), 0, 16);
    }

    public function createOrder()
    {
        $this->validate([
            'payment_method' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'nullable|email',
            'reference' => 'required|string|unique:orders,reference'
        ]);

        //create order record
        $order = auth()->user()->orders()->create([
                        'payment_method' => $this->payment_method,
                        'customer_phone' => $this->customer_phone,
                        'reference' => $this->reference,
                        'customer_name' => $this->customer_name,
                        'customer_email' => $this->customer_email,
                        'status' => 'completed',
                        'store_id' => auth()->user()->store->id,
                    ]);
        
        // add sales to order
        $order->sales()->createMany(Cart::content()->map(function($item){
            return [
                'stock_id' => $item->stock_id,
                'quantity' => $item->quantity,
                'sale_price' => $item->sale_price,
                'stock_price' => $item->stock_price,
            ];
        })->toArray());

        // order created show card
        Cart::clear();
        return redirect()->route('dashboard.order.view',['order' => $order->id]);
    }

    #[Computed]
    public function orderItems()
    {
        return Cart::content()?->reverse();
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.pages.auth.order.create');
    }
}
