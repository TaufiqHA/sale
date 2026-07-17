<?php

namespace App\Models;

use Database\Factories\ProductionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'counter_id',
    'product_id',
    'production_date',
    'total_cost',
    'total_result',
    'hpp',
    'selling_price',
    'estimated_profit',
    'notes',
    'status',
])]
class Production extends Model
{
    /** @use HasFactory<ProductionFactory> */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'draft',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'production_date' => 'date',
            'total_cost' => 'decimal:2',
            'total_result' => 'integer',
            'hpp' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'estimated_profit' => 'decimal:2',
        ];
    }

    /**
     * Get the counter that owns the production.
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    /**
     * Get the product that owns the production.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
