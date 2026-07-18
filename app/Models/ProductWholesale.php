<?php

namespace App\Models;

use Database\Factories\ProductWholesaleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'minimum_qty',
    'wholesale_price',
])]
class ProductWholesale extends Model
{
    /** @use HasFactory<ProductWholesaleFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'minimum_qty' => 'integer',
            'wholesale_price' => 'decimal:2',
        ];
    }

    /**
     * Get the product that owns this wholesale price tier.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
