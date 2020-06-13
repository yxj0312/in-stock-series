<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class Target implements Client
{
    public function checkAvailabilability(Stock $stock)
    {
        // return  Http::get('http://foo.test')->json();
    }
}