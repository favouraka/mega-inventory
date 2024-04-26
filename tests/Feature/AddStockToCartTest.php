<?php

namespace Tests\Feature;

use App\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Livewire\Pages\Auth\Stock\Index;
use App\Models\User;
use Livewire\Livewire;

class AddStockToCartTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

   /** @test */
   public function it_renders_page_successfully()
   {
        // Test
        $store = \App\Models\Store::factory()->create()->id;
        $user =  User::factory()->create([
            'store_id' => $store
        ]);
        \App\Models\Stock::factory(60)->create([
            'product_id' => \App\Models\Product::factory()->create(),
            'store_id' => $store
        ]);
        $response = $this->actingAs($user)->get('/dashboard/stock');
        $response->assertStatus(200);
        $response->assertSeeLivewire(Index::class);
   }

   /** @test */
   public function it_adds_to_cart_successfully()
   {
       // Test
       $store = \App\Models\Store::factory()->create()->id;

       $user =  User::factory()->create([
           'store_id' => $store
       ]);

       \App\Models\Stock::factory(60)->create([
           'product_id' => \App\Models\Product::factory()->create(),
           'store_id' => $store
       ]);

       $stock = \App\Models\Stock::firstOrFail();

       Livewire::actingAs($user)->test(Index::class)
                ->assertSee($stock->product->title)
                ->call('add_to_cart', $stock)
                ->assertCount('carts', 1);
   }
   
}
