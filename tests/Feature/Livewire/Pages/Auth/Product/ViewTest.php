<?php

namespace Tests\Feature\Livewire\Pages\Auth\Product;

use App\Livewire\Pages\Auth\Product\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(View::class)
            ->assertStatus(200);
    }

    /** @test */
    public function it_show_product_basic_information()
    {
        // seed 10 products that each has 3 images in the database 
        $products = \App\Models\Product::factory()->count(10)->hasImages(3)->create();

        // pick a random prroduct as var $view_product 
        $view_product = $products->random();

        // test view product page
        Livewire::test(View::class, ['product' => $view_product])
            ->assertSet('product', $view_product)
            // assert see html id called #basicInfo
            ->assertSeeHtml('id="basicInfo"')
            ->assertSet('product', $view_product);

    }
    
}
