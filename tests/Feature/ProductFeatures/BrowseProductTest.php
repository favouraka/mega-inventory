<?php

namespace Tests\Feature\ProductFeatures;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Livewire\Pages\Auth\Product\Index;
use Livewire\Livewire;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class BrowseProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    /** @test */
    public function it_renders_product_results()
    {
        // Test page loads
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('dashboard.product.index'));

        // test sees component 
        $response->assertStatus(200)->assertSeeLivewire(Index::class);

        Livewire::test(Index::class)->assertStatus(200)->assertViewHas('products', Product::count());
    }

    /** @test */
    public function it_shows_primary_product_details()
    {
        // sees products 
        $products = Product::factory(10)->create();

        Livewire::test(Index::class)->assertSee([
            $products->latest()->title,
        ]);
    }

    /** @test */
    public function it_filters_by_search()
    {
        Product::factory(10)->hasImages(3)->create();
        // test products are created
        $products = Product::all();
        // dd($products->count());
        $this->assertEquals(10, $products->count());
        // Test filter by search
        $search = $products->first()->title;
        Livewire::test(Index::class)
            ->assertCount('products', 10)
            ->set('search', $search)
            ->assertSee($search)
            ->assertCount('products', 1)
            ->assertSee($products->first()->sku_code);
    }

    /** @test */
    public function it_filters_by_product_name()
    {
        
        Product::factory(10)->hasImages(3)->create();
        // test products are created and orderby name
        $products = Product::orderby('title','asc')->get();
        // dd($products->count());
        $this->assertEquals(10, $products->count());
        // Test filter by name
        Livewire::test(Index::class)
            ->assertCount('products', 10)
            ->set('filters.title', 'asc')
            ->assertSet('filters.title', 'asc')
            ->assertSeeInOrder([
                $products[0]->title,
                $products[1]->title,
            ]);
    }

    /** @test */
    public function it_filters_by_naira_price()
    {
        Product::factory(10)->hasImages(3)->create();
        // test products are created and orderby name
        $products = Product::orderby('price_ngn','asc')->get();
        // test the filter
        Livewire::test(Index::class)->set('filters.price_ngn', 'asc')
                ->assertSet('filters.price_ngn', 'asc')
                ->assertSeeInOrder([
                    $products[0]->price_ngn,
                    $products[1]->price_ngn,
                ]);
    }
    
    
    
    
    
}
