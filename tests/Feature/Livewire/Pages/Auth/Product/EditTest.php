<?php

namespace Tests\Feature\Livewire\Pages\Auth\Product;

use Illuminate\Http\UploadedFile;
use App\Livewire\Pages\Auth\Product\Edit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EditTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Edit::class)
            ->assertStatus(200);
    }

    /** @test */
    public function it_shows_product_info()
    {
        $prod = Product::factory()->create();
        
        // Test
        Livewire::test(Edit::class, [$prod->id])
            ->assertSet('title', $prod->title)
            ->assertSet('description', $prod->description)
            ->assertSet('category_id', $prod->category_id)
            ->assertSet('weight', $prod->weight)
            ->assertSet('height', $prod->height)
            ->assertSet('length', $prod->length)
            ->assertSet('width', $prod->width)
            ->assertSet('upc_code', $prod->upc_code)
            ->assertSet('sku_code', $prod->sku_code)
            ->assertSet('price_ngn', $prod->price_ngn)
            ->assertSet('price_cfa', $prod->price_cfa)
            ->assertSet('color', $prod->color)
            ->assertSet('size', $prod->size)
            ->assertSet('batch', $prod->batch)
            ->assertSet('manufacturer', $prod->manufacturer)
            ->assertSet('brand', $prod->brand)
            ->assertSet('production_date', $prod->production_date)
            ->assertSet('expiry_date', $prod->expiry_date);
    }

    /** @test */
    public function it_saves_correctly_function()
    {
        $product = Product::factory()->hasImages(4)->create();
        $expiry_new = Carbon::now()->addMonths(7);
      
        // Test
        Livewire::test(Edit::class, [$product->id])
            ->assertSet('title', $product->title)
            ->assertSet('description', $product->description)
            ->assertSet('category_id', $product->category_id)
            ->assertSet('weight', $product->weight)
            ->assertSet('height', $product->height)
            ->assertSet('length', $product->length)
            ->assertSet('width', $product->width)
            ->assertSet('upc_code', $product->upc_code)
            ->assertSet('sku_code', $product->sku_code)
            ->assertSet('price_ngn', $product->price_ngn)
            ->assertSet('price_cfa', $product->price_cfa)
            ->assertSet('color', $product->color)
            ->assertSet('size', $product->size)
            ->assertSet('batch', $product->batch)
            ->assertSet('manufacturer', $product->manufacturer)
            ->assertSet('brand', $product->brand)
            ->assertSet('production_date', $product->production_date)
            ->assertSet('expiry_date', $product->expiry_date)
            ->set('expiry_date', $expiry_new )
            ->assertSet('expiry_date', $expiry_new)
            ->call('save')
            ->assertHasNoErrors();
             // Find or fail product
            $product = Product::findOrFail($product->id);

            // assert the $expiry new and current $product->expiry_date class are the same month and year when both values
            // are parsed in Carbon::parse()
            $this->assertEquals(Carbon::parse($expiry_new)->format('Y-m'), Carbon::parse($product->expiry_date)->format('Y-m'));

    }

    /** @test */

    /** @test */
    public function it_can_add_images_to_product()
    {
        // Fake the storage
        Storage::fake('public');

        // creates a new product that has 3 images from the product factory method
        $product = Product::factory()->hasImages(3)->create();

        // test livewire component to set uploaded image
        Livewire::actingAs(User::factory()->create())->test(Edit::class, [$product->id])
            ->set('upload_image', UploadedFile::fake()->image('fake_image.jpg', 512, 512))
            ->call('uploadImage')
            ->asserthasNoErrors()
            ->assertCount('images', 4)
            ;

        
        // find or fail product with $product->id
        $product = Product::findOrFail($product->id);

        // assert that the product has 2 images
        $this->assertCount(4, $product->images);
    }

    /** @test */
    public function it_removes_images_from_products()
    {
        // fake the storage
        Storage::fake('public');

        // create a new product that has 3 images from the product factory method
        $product = Product::factory()->hasImages(3)->create();

        // test livewire component to call removeImage with the parameter as the index of the last image
        Livewire::actingAs(User::factory()->create())->test(Edit::class, [$product->id])
            ->call('removeImage', 2)
            ->asserthasNoErrors()
            ->assertCount('images', 2)
            ;

        // find or fail product with $product->id
        $product = Product::findOrFail($product->id);

        // assert that the product has 2 images
        $this->assertCount(2, $product->images);
    }
    
    
    
}
