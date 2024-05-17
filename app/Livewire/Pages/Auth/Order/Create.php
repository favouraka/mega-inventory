<?php

namespace App\Livewire\Pages\Auth\Order;

use App\Facades\Cart;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\{Title, Computed};
use App\Traits\WithCart;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

#[Title('New Order')]
class Create extends Component implements HasActions, HasForms
{

    use WithCart, InteractsWithActions, InteractsWithForms;

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
        return Product::when($this->search, function($query, $search){
                                    $query->where('title','like','%'.$search.'%')
                                        ->orWhere('upc_code','like','%'.$search.'%')
                                        ->orWhere('sku_code','like','%'.$search.'%');
                                })->latest()->take(6)->get()->sortByDesc(fn($products) => $products->inventories()->first()?->quantity);
    }

    public function createAction(): Action
    {
        return Action::make('create')
                        ->icon('heroicon-o-credit-card')
                        ->label('Place New Order')
                        ->modalHeading('Add customr details')
                        ->modalDescription('Please provide customer details to complete the order')
                        ->modalIcon('heroicon-o-user-plus')
                        ->color('info')
                        ->failureNotificationTitle('An error occured')
                        ->action(function(array $data){
                            $this->customer_name = $data['customer_name'];
                            $this->customer_phone = $data['customer_phone'];
                            $this->customer_email = $data['customer_email'];
                            $this->create();
                        })
                        ->form([
                            TextInput::make('customer_name')
                                ->label('Name')
                                ->placeholder('John Doe')
                                ->required(),
                            TextInput::make('customer_email')
                                ->label('Email')
                                ->placeholder('Email address'),
                            TextInput::make('customer_phone')
                                ->label('Phone number')
                                ->required()
                                ->placeholder('Phone number')
                        ]);
    }

    public function mount()
    {
        $this->reference = 'ORD-' . substr(md5(time()), 0, 16);
    }

    public function create()
    {
        $validated = $this->validate([
            'payment_method' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'nullable|email',
            'reference' => 'required|string|unique:orders,reference'
        ]);

        // checks if items added to cart are of right quantity
        $this->checkStocks();

        // stop propagation if theres any error in the cart
        if($this->getErrorBag()->any()){
            return;
        } else {
            // create order record
            $order = auth()->user()->orders()->create([
                            'payment_method' => $validated['payment_method'],
                            'customer_phone' => $validated['customer_phone'],
                            'reference' => $validated['reference'],
                            'customer_name' => $validated['customer_name'],
                            'customer_email' => $validated['customer_email'],
                            'status' => 'completed',
                            'store_id' => auth()->user()->store->id,
                        ]);
            
            // add sales to order
            $order->sales()->createMany(
                Cart::content()
                    ->map( function($item){
                            return [
                                'inventory_id' => $item->inventory_id,
                                'quantity' => $item->quantity,
                                'sale_price' => $item->sale_price,
                                'stock_price' => $item->stock_price,
                            ];
                        })->toArray());
    
            // order created show card
            Cart::clear();
            // 
            Notification::make()
                ->title('Order created successfully')
                ->success()
                ->send();
            return redirect(ViewOrder::getUrl(['record' => $order->id]));
        }
        
    }

   
    private function checkStocks()
    {
        // checks if Sale models in cart have the adequate quantity
        Cart::content()->each(function($item){
            $stock = auth()->user()->store->inventories()->find($item->inventory_id);
            if($stock->quantity < $item->quantity){
                $errorMessage = 'The quantity of '.$stock->product->title.' is not enough';
                $this->addError('stock_quantity_'.$item->inventory_id, $errorMessage);
                Notification::make()->title('Not enough products in stock')
                    ->body($errorMessage)
                    ->danger()
                    ->send();
            } else {
                // deducts the qty from $stock model and updates
                $stock->quantity -= $item->quantity;
                $stock->save();
            }
        });

        
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
