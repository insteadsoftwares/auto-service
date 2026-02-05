<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleModele extends Model
{
    protected $table = 'aus_vehicle_modeles';

    protected $fillable = [
        'modele',
        'brand_id',
        'type_id',
        'active',
    ];

	protected $casts = [
        'modele' => 'string',
        'brand_id' => 'int',
        'type_id' => 'int',
        'active' => 'boolean',
    ];

	public function brand()
    {
        return $this->belongsTo(VehicleBrand::class);
    }
	
	public function type()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
