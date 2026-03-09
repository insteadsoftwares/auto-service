<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarageWorkingHours extends Model
{
	
    protected $table = 'aus_garage_working_hours';

    protected $fillable = [
        'garage_id',
        'working_day_id',
        'start_time',
        'end_time',
    ];

	protected $casts = [
        'garage_id' => 'int',
        'working_day_id' => 'int',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

	public function garage()
    {
        return $this->belongsTo(Garage::class);
    }

	public function garageWorkingDays()
    {
        return $this->belongsTo(GarageWorkingDays::class, 'working_day_id');
    }
	
}