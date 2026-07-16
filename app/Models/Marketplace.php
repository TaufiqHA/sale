<?php

namespace App\Models;

use Database\Factories\MarketplaceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Marketplace extends Model
{
    /** @use HasFactory<MarketplaceFactory> */
    use HasFactory;
}
