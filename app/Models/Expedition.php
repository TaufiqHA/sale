<?php

namespace App\Models;

use Database\Factories\ExpeditionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
])]
class Expedition extends Model
{
    /** @use HasFactory<ExpeditionFactory> */
    use HasFactory;
}
