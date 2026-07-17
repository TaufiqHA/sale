<?php

namespace App\Models;

use Database\Factories\ProductionItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'production_id',
    'description',
    'unit_price',
    'qty',
    'total',
])]
class ProductionItem extends Model
{
    /** @use HasFactory<ProductionItemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'qty' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Get the production that owns the production item.
     */
    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }
}
