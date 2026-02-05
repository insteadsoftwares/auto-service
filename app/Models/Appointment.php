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
    ];

	protected $casts = [
        'client_id' => 'int',
        'garage_id' => 'int',
        'service_id' => 'int',
        'vehicle_id' => 'int',
        'appointment_date' => 'date',
        'appointment_time' => 'string',
        'status' => 'string',
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
}
