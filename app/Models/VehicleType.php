<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{

    protected $table = 'aus_vehicle_types';

    protected $fillable = [
        'type',
    ];

	protected $casts = [
        'type' => 'string',
    ];

}
