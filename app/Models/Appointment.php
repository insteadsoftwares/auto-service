<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'aus_appointments';

    protected $fillable = [
        'client_id',
        'garage_id',
        'service_id',
        'vehicle_id',
        'appointment_date',
        'appointment_time',
        'status',
        'guest_name',
        'guest_phone',
        'guest_vehicle_type_id',
        'guest_vehicle_brand_id',
        'guest_vehicle_model_id',
        'is_client',
    ];

	protected $casts = [
        'client_id' => 'int',
        'garage_id' => 'int',
        'service_id' => 'int',
        'vehicle_id' => 'int',
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'status' => 'string',
        'guest_name' => 'string',
        'guest_phone' => 'string',
        'guest_vehicle_type_id' => 'int',
        'guest_vehicle_brand_id' => 'int',
        'guest_vehicle_model_id' => 'int',
        'is_client' => 'boolean',
    ];

	public function client()
    {
        return $this->belongsTo(User::class);
    }

	public function garage()
    {
        return $this->belongsTo(Garage::class, 'garage_id');
    }
	
	public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
	
	public function vehicle()
    {
        return $this->belongsTo(ClientVehicle::class, 'vehicle_id');
    }

	public function guestVehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'guest_vehicle_type_id');
    }
	
	public function guestVehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'guest_vehicle_brand_id');
    }
	
	public function guestVehicleModele()
    {
        return $this->belongsTo(VehicleModele::class, 'guest_vehicle_model_id');
    }
}
