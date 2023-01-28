<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = 'products';

    public $fillable = [
        'name',
        'description',
        'price'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'price' => 'float'
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:65535',
        'price' => 'required|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
