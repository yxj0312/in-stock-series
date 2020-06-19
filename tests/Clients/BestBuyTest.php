<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RetailerWithProductSeeder;
use Tests\TestCase;

/**
 * @group api
 */
class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_a_product()
    {
        // given I have a product
        $this->seed(RetailerWithProductSeeder::class);

        // update returns boolean, thus we use tap here
        $stock = tap(Stock::first())->update([
            'sku' => '6364255',// Nintendo switch sku
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-neon-red-neon-blue-joy-con/6364255.p?skuId=6364255'
        ]);

        // with stock at BestBuy
        // if I use the BestBuy client to track that stock/sku
        // it should return the appropriate StockStatus
        try {
            $stockStatus = (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('Failed to track the BestBuy API properly.');
        }

        $this->assertTrue(true);
    }
}