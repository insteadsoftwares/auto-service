<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarageWorkingDays extends Model
{
	
    protected $table = 'aus_garage_working_days';

    protected $fillable = [
        'garage_id',
        'day_of_week',
        'is_open',
    ];

	protected $casts = [
        'garage_id' => 'int',
        'day_of_week' => 'int',
        'is_open' => 'boolean',
    ];

	public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
	
	public function garageWorkingHours()
    {
        return $this->hasMany(GarageWorkingHours::class, 'working_day_id');
    }
	
}