<?php

namespace App\Services;
use App\Models\Stock;

class CartService
{
    private $items = []; // Property for an array of items in the cart.

    public const DEFAULT_CART = 'order-cart';
    public const MIN_QUANTITY = 1;
    
    /**
     * CartService constructor.
     *
     * @param string|null $key The key used to identify the cart. If not provided, a new UUID will be generated.
     */
    public function __construct()
    {
        $this->items = collect(session(self::DEFAULT_CART) ?? []);
    }

    /**
     * Checks if a product exists on the cart.
     *
     * @param string $productId The ID of the product to check.
     * @param array $cartItems The array of cart items.
     * @return bool Returns true if the product exists on the cart, false otherwise.
     */
    function exists($stock): bool {
        return $this->items->where('stock_id', $stock)->count() ? true : false;
    }

    /**
     * Add an item to the cart.
     *
     * @param Stock | int $stock The item to add to the cart.
     */
    public function add(Stock | int $stock, int $quantity = self::MIN_QUANTITY)    
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;
        // check if sale exists on cart

        
        if (collect($this->items)->where('stock_id', $stock->id)->isNotEmpty()) {
            $this->items = collect($this->items)->map(function ($item) use ($stock, $quantity) {
                if ($item['stock_id'] === $stock->id) {
                    $item['quantity'] += $quantity;
                }
                return $item;
            });
        } else {
            $sale = $stock->sales()->make([
                'quantity' => $quantity,
                'sale_price' => $stock->product->price_ngn,
                'stock_price' => $stock->product->price_ngn,
            ]);
            
            $this->items = collect($this->items)->push($sale);
        }
        
        session()->put(self::DEFAULT_CART, $this->items);
    }

    public function updateQty($stock, $quantity)
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;

        if (collect($this->items)->where('stock_id', $stock->id)->isNotEmpty()) {
            $this->items = collect($this->items)->map(function ($item) use ($stock, $quantity) {
                if ($item['stock_id'] === $stock->id) {
                    $item['quantity'] = $quantity;
                }
                return $item;
            });
        } else {
            $sale = $stock->sales()->make([
                'quantity' => $quantity,
                'sale_price' => $stock->product->price_ngn,
                'stock_price' => $stock->product->price_ngn,
            ]);
            
            $this->items = collect($this->items)->push($sale);
        }
        
        session()->put(self::DEFAULT_CART, $this->items);
    }

    public function content()
    {
        return $this->items;
    } 

    /**
     * Remove a stock item from the cart.
     *
     * @param Stock|int $stock The stock item or its ID to be removed.
     * @return void
     */
    public function remove(Stock |int $stock)
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;
        if(collect($this->items)->where('stock_id', $stock->id)->isEmpty()) {
            return;
        }
        $this->items = collect($this->items)->filter(function($item) use($stock){
                            return $item['stock_id'] !== $stock->id;
                        });

        session()->put(self::DEFAULT_CART, $this->items);        
    }


    public function subtract(Stock | int $stock, int|string $quantity = self::MIN_QUANTITY)
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;
        $this->items = collect($this->items)
                        ->filter(function ($item) use ($stock, $quantity) {
                            if ($item['stock_id'] === $stock->id) {
                                $item['quantity'] -= $quantity;
                            }
                            return $item;
                        });        

        session()->put(self::DEFAULT_CART, $this->items);
    }

    public function updatePrice(Stock | int $stock, $price)
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;
        $this->items = collect($this->items)
                        ->map(function ($item) use ($stock, $price) {
                            if ($item['stock_id'] === $stock->id) {
                                $item['sale_price'] = $price;
                            }
                            return $item;
                        });      

        session()->put(self::DEFAULT_CART, $this->items);
    }

    public function resetPrice(Stock | int $stock)
    {
        $stock = is_int($stock) ? Stock::find($stock) : $stock;
        $this->items = collect($this->items)
                        ->map(function ($item) use ($stock) {
                            if ($item['stock_id'] === $stock->id) {
                                $item['sale_price'] = $stock->product->price_ngn;
                            }
                            return $item;
                        });      

        session()->put(self::DEFAULT_CART, $this->items);
    }


    public function clear()
    {
        session()->forget(self::DEFAULT_CART);
        $this->items = collect([]);
    }
}