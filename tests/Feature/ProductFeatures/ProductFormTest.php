<?php

namespace Tests\Feature\ProductFeatures;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Livewire\Pages\Auth\Product\Create;
use Livewire\Livewire;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductFormTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    
    // 
    public function test_renders_product_form(): void
    {
        $user = User::factory()->create(['username' => 'administrator']);
        $this->actingAs($user);
        $response = $this->get('/dashboard/product/create');
        $response->assertStatus(200);
        $response->assertSee('Create New Product');
    }

    public function test_fills_all_properties()
    {
        $user = User::factory()->create(['username' => 'administrator']);

        $new_product = Product::factory()->make();

        $this->assertNotEmpty($new_product);

        Livewire::actingAs($user)->test(Create::class)
                ->assertSeeInOrder(['name','title'])
                ->assertSeeInOrder(['name','description'])
                ->assertSeeInOrder(['name','upload_image'])
                ->assertSeeInOrder(['name','weight'])
                ->assertSeeInOrder(['name','height'])
                ->assertSeeInOrder(['name','width'])
                ->assertSeeInOrder(['name','length'])
                ->assertSeeInOrder(['name','category'])
                ->assertSeeInOrder(['name','sku_code'])
                ->assertSeeInOrder(['name','upc_code'])
                ->assertSeeInOrder(['name','price_ngn'])
                ->assertSeeInOrder(['name','price_cfa'])
                // TITLE AND DESCRIPTION
                // title
                ->set('title', $new_product['title'])
                ->assertSet('title', $new_product['title'])
                // description
                ->set('description', $new_product['description'])
                ->assertSet('description', $new_product['description'])
                // SHIPPING INFORMATION
                // weight
                ->set('weight', $new_product['weight'])
                ->assertSet('weight', $new_product['weight'])
                // height
                ->set('height', $new_product['height'])
                ->assertSet('height', $new_product['height'])
                // length
                ->set('length', $new_product['length'])
                ->assertSet('length', $new_product['length'])
                // width
                ->set('width', $new_product['width'])
                ->assertSet('width', $new_product['width'])
                // INVENTORY INFORMATION
                // sku code
                ->set('upc_code', $new_product['upc_code'])
                ->assertSet('upc_code', $new_product['upc_code'])
                ->set('sku_code', $new_product['sku_code'])
                ->assertSet('sku_code', $new_product['sku_code'])
                // category
                ->set('category_id', $new_product->category->id)
                ->assertSet('category_id', $new_product['category']->id)
                // PRICES
                // price naira
                ->set('price_ngn', $new_product['price_ngn'])
                ->assertSet('price_ngn', $new_product['price_ngn'])
                // price cfa
                ->set('price_cfa', $new_product['price_cfa'])
                ->assertSet('price_cfa', $new_product['price_cfa']);
    }

    /** @test */
    public function test_displays_validation_errors()
    {
        $user = User::factory()->create(['username' => 'administrator']);

        Livewire::actingAs($user)->test(Create::class)->call('save')
                ->assertHasErrors('images')
                ->assertHasErrors('title')
                ->assertHasErrors('description')
                ->assertHasErrors('weight')
                ->assertHasErrors('height')
                ->assertHasErrors('width')
                ->assertHasErrors('length')
                ->assertHasErrors('upc_code')
                ->assertHasErrors('category_id')
                ->assertHasErrors('price_ngn')
                ->assertHasErrors('price_cfa');
    }

    /** @test */
    public function test_uploads_image_in_component()
    {
        Storage::fake('public');

        $user = User::factory()->create(['username' => 'administrator']);

        Livewire::actingAs($user)->test(Create::class)
                ->set('upload_image', UploadedFile::fake()->image('product-image-1'))
                ->call('uploadImage')
                ->assertCount('images', 1);
    }


    /** @test */
    public function test_validates_image_uploads()
    {
        // Test
        $user = User::factory()->create();
        Storage::fake('public');

        Livewire::actingAs($user)->test(Create::class)
                // test empty upload
                ->call('uploadImage')
                ->assertHasErrors('upload_image')
                // test unsupported file
                ->set('upload_image', UploadedFile::fake()->create('sound.mp3'))
                ->call('uploadImage')
                ->assertHasErrors(['upload_image' => ['image']])
                // test large file
                ->set('upload_image', UploadedFile::fake()->image('picture.png')->size(3000))
                ->call('uploadImage')
                ->assertHasErrors(['upload_image' => ['max:2048']]);
    }

    /** @test */
    public function it_saves_the_images_to_the_product()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $new_product = Product::factory()->make();
        
        $file = UploadedFile::fake()->image('upload.png')->size(1024);
        Livewire::actingAs($user)->test(Create::class)
                ->set('upload_image', $file)
                ->call('uploadImage')
                ->assertCount('images', 1)
                // TITLE AND DESCRIPTION
                // title
                ->set('title', $new_product['title'])
                ->assertSet('title', $new_product['title'])
                // description
                ->set('description', $new_product['description'])
                ->assertSet('description', $new_product['description'])
                // SHIPPING INFORMATION
                // weight
                ->set('weight', $new_product['weight'])
                ->assertSet('weight', $new_product['weight'])
                // height
                ->set('height', $new_product['height'])
                ->assertSet('height', $new_product['height'])
                // length
                ->set('length', $new_product['length'])
                ->assertSet('length', $new_product['length'])
                // width
                ->set('width', $new_product['width'])
                ->assertSet('width', $new_product['width'])
                // INVENTORY INFORMATION
                // sku code
                ->set('upc_code', $new_product['upc_code'])
                ->assertSet('upc_code', $new_product['upc_code'])
                ->set('sku_code', $new_product['sku_code'])
                ->assertSet('sku_code', $new_product['sku_code'])
                // category
                ->set('category_id', $new_product->category->id)
                ->assertSet('category_id', $new_product['category']->id)
                // PRICES
                // price naira
                ->set('price_ngn', $new_product['price_ngn'])
                ->assertSet('price_ngn', $new_product['price_ngn'])
                // price cfa
                ->set('price_cfa', $new_product['price_cfa'])
                ->assertSet('price_cfa', $new_product['price_cfa'])
                ->call('save');

        $product = Product::first();

        $this->assertCount(1, $product->images);
    }    
}
