<?php

namespace Tests\Feature;

use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RetailerWithProduct;
use RetailerWithProductSeeder;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());
        // When
        // I trigger the php artisan track command
        // And assuming the stock is available now
        Http::fake(function(){
            return [
                'available' => true,
                'price' => 29900
                ];
        });

        $this->artisan('track')
            ->expectsOutput('All done!');

        // Then
        // The stock details should be refreshed
        $this->assertTrue(Product::first()->inStock());
    }
}
