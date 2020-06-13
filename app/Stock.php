<?php

namespace App;

use App\Clients\BestBuy;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        $class = "App\\Clients\\" . Str::studly($this->retailer->name);

        $status = (new $class)->checkAvailabilability($this);
        // Hit an API endpoint for the associated retailer (strategies pattern could be used here)
        // Fetch the up-to-date details for the item
        // And then refresh the current stock record.
        // if ($this->retailer->name === 'Best Buy') {
        //     $status = (new BestBuy())->checkAvailabilability($this);
        // }

        // if ($this->retailer->name === 'Target') {
        //     $status = (new Target())->checkAvailabilability($this);
        // }

        $this->update([
            'in_stock' => $status->available,
            'price' => $status-> price
        ]);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
