<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'name' => 'array',
        'category_id' => 'integer',
        'price' => 'float',
        'picture' => 'string',
        'description' => 'array',
        'in_stock' => 'boolean',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];
}
