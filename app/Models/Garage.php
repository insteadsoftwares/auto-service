<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Garage extends Model
{
	use SoftDeletes;

    protected $table = 'aus_garages';

    protected $fillable = [
        'name',
        'description',
        'address',
        'technician_id',
    ];

	protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'address' => 'string',
        'technician_id' => 'int',
    ];

	public function technician()
    {
        return $this->belongsTo(User::class);
    }

	public function garageServices()
    {
        return $this->hasMany(GarageService::class, 'garage_id');
    }

	public function garageSpecialties()
    {
        return $this->hasMany(GarageSpecialty::class, 'garage_id');
    }

	public function appointments()
    {
        return $this->hasMany(Appointment::class, 'garage_id');
    }
}
