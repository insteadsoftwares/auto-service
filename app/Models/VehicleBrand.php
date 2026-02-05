<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $table = 'aus_vehicle_brands';

    protected $fillable = [
        'name',
        'active',
    ];

	protected $casts = [
        'name' => 'string',
        'active' => 'boolean',
    ];

}
