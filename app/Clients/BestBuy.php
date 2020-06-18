<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $url = $this->endpoint($sku, $apiKey);
        
        $results = Http::get($url)->json();

        return new StockStatus(
            $results['available'],
            $results['price']
        );
    }

    protected function endpoint($sku, $apiKey): String
    {
        $url = "https://www.bestbuy.com/site/nintendo-switch-32gb-console-neon-red-neon-blue-joy-con/6364255.p?skuId=6364255";
        return $url
    }
}