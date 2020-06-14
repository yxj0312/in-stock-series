<?php

namespace App;

use App\Clients\BestBuy;
use App\Clients\ClientException;
// realtime facades
use Facades\App\Clients\ClientFactory;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        // Hit an API endpoint for the associated retailer (strategies pattern could be used here)
        // Fetch the up-to-date details for the item
        // And then refresh the current stock record.
        $status = $this->retailer
            ->client()
            ->checkAvailabilability($this);

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
