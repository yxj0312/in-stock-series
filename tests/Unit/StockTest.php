<?php

namespace Tests\Unit;

use App\Clients\ClientException;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RetailerWithProductSeeder;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        // given I have a retailer with stock
        $this->seed(RetailerWithProductSeeder::class);

        // And if the retailer doesn't have a client class
        Retailer::first()->update(['name' => 'Foo Retailer']);

        // Then an exception should be thrown
        $this->expectException(ClientException::class);

        // If I track that stock
        Stock::first()->track();
        
    }
}
