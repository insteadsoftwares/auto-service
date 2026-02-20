<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarageSpecialty extends Model
{
	use SoftDeletes;

    protected $table = 'aus_garage_specialties';

    protected $fillable = [
        'garage_id',
        'vehicle_type_id',
        'vehicle_brand_id',
        'vehicle_modele_id',
    ];

	protected $casts = [
        'garage_id' => 'int',
        'vehicle_type_id' => 'int',
        'vehicle_brand_id' => 'int',
        'vehicle_modele_id' => 'int',
    ];

	public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
	
	public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
	
	public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class);
    }
	
	public function vehicleModele()
    {
        return $this->belongsTo(VehicleModele::class);
    }
}
