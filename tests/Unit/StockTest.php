<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use App\Retailer;
use App\Stock;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
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

    /** @test */
    function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // ClientFactory to determine the appropriate Client
        // checkAvailability()
        // ['available' => true, 'price' => 9900]

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(
            new StockStatus($available = true, $price = 9900)
        );
        


        // Option 1: 

        // ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

        // Option 2:
        
        // ClientFactory::shouldReceive('make')->andReturn(new class implements Client
        //     {
        //         public function checkAvailability(Stock $stock): StockStatus
        //         {
        //             return new StockStatus($available = true, $price = 9900);
        //         }
        //     });

        // Option 3: 

        // $clientMock = Mockery::mock(Client::class);
        // $clientMock->shouldReceive('checkAvailability')->andReturn(new StockStatus($available = true, $price = 9900));

        // ClientFactory::shouldReceive('make')->andReturn($clientMock);


        $stock = tap(Stock::first())->track();
        
        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}

class FakeClient implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        return new StockStatus($available = true, $price = 9900);
    }
}