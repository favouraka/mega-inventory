<?php

namespace App\Traits;

use App\Facades\Cart;

trait WithCart
{
    // Trait code goes here
    

    /**
     * Adds the sale item to the cart using the stock ID.
     *
     * @param  int  $stockId
     * @param int $quantity
     * @return void
     */
    public function addItem($stockId)
    {
        Cart::add($stockId);
    }

    public function updatePrice($stockId, $price)
    {
        Cart::updatePrice($stockId, $price);
    }

    public function updateQuantity($stockId, $quantity)
    {
        Cart::add($stockId, $quantity);
    }

    public function resetPrice($stockId)
    {
        Cart::resetPrice($stockId);
    }

    public function subtractItem($stockId)
    {
        Cart::subtract($stockId);
    }

    public function clearCart()
    {
        Cart::clear();
    }

    /**
     * Removes the sale item from the cart using the stock ID.
     *
     * @param  int  $stockId
     * @return void
     */
    public function removeFromCart($stockId)
    {
        Cart::remove($stockId);
    }

}