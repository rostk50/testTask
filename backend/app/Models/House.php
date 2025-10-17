<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class House extends Model
{
    protected $table = 'houses';

    public $timestamps = false;

    protected $fillable = ['name', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages'];

    protected $casts = [
        'price' => 'int',
        'bedrooms' => 'int',
        'bathrooms' => 'int',
        'storeys' => 'int',
        'garages' => 'int',
    ];
}
