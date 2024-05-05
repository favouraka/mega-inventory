<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use Livewire\Livewire;
use App\Models\Stock;
use App\Models\Product;
use Livewire\Volt\Volt;

class InvoiceFeatureTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->for(Store::factory(), 'store')->create();
    }
    
    /**
     * A basic feature test example.
     */
    public function test_loads_order_view(): void
    {
        $order = $this->user->orders()->save(Order::factory()->make());
        // dd($order);
        $response = $this->actingAs($this->user)->get('/dashboard/order/view/'.$order->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function shows_order_information_page()
    {
        // Test
        // $sales = Sale::factory()->count(5)->create()
        $order = Order::factory()
                    ->for($this->user, 'user')
                    ->has(Sale::factory()->for(
                            Stock::factory()
                            ->for(Product::factory(), 'product')
                            ->for($this->user->store, 'store')
                        )->count(2), 'sales')->create();
        

        Livewire::test('pages.auth.order.view', ['order' => $order])
            ->assertSee('Order Information')
            ->assertCount('order.sales', 2);
    }

    /** @test */
    public function shows_all_orders_created()
    {
        // Test
        $orders = Order::factory(20)->for($this->user, 'user')->has(
            Sale::factory(4)->for(
                Stock::factory()
                    ->for($this->user->store, 'store')
                    ->for(Product::factory(), 'product')
            ), 'sales'
        )->create();

        Volt::actingAs($this->user)->test('pages.auth.order.index')
                ->assertSee('Orders')
                ->assertCount('orders', 20)
                ->assertSee([$orders->first()->customer_name, $orders->first()->customer_phone]);
    }
    
    
}
