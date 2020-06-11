<?php

namespace Tests\Feature;

use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_product_stock()
    {
        // Given
        // I have a product with stock
        $switch = Product::create(['name' => 'Nintendo Switch']);

        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 10000,
            'url' => 'http://foo.com',
            'sku' => '123456',
            'in_stock' => false,
        ]);

        $bestBuy->addStock($switch, $stock);
        
        $this->assertFalse($stock->in_stock);
        // When
        // I trigger the php artisan track command
        // And assuming the stock is available now
        Http::fake(function(){
            return [
                'available' => true,
                'price' => 29900
                ];
        });
        $this->artisan('track');

        // Then
        // The stock details should be refreshed
        $this->assertTrue($stock->fresh()->in_stock);
    }
}
