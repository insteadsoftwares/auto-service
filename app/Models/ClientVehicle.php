<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientVehicle extends Model
{
	use SoftDeletes;

    protected $table = 'aus_client_vehicles';

    protected $fillable = [
        'client_id',
        'type_id',
        'brand_id',
        'modele_id',
        'registration_number',
        'year',
        'description',
    ];

	protected $casts = [
        'client_id' => 'int',
        'type_id' => 'int',
        'brand_id' => 'int',
        'modele_id' => 'int',
        'registration_number' => 'string',
        'year' => 'int',
        'description' => 'string',
    ];

	public function client()
    {
        return $this->belongsTo(User::class);
    }

	public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
    }
	
	public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }
	
	public function vehicleModele()
    {
        return $this->belongsTo(VehicleModele::class, 'modele_id');
    }
}
