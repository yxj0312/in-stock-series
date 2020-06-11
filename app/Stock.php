<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

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
        if ($this->retailer->name === 'Best Buy') {
            $results = Http::get('http://foo.test')->json();

            $this->update([
                'in_stock' => $results['available'],
                'price' => $results['price']
            ]);
        }
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
