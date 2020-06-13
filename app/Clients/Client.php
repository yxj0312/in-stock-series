<?php

namespace App\Clients;

use App\Stock;

interface Client
{
    public function checkAvailabilability(Stock $stock);
}