<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarageLeave extends Model
{
	
    protected $table = 'aus_garage_leaves';

    protected $fillable = [
        'garage_id',
        'start_date',
        'end_date',
    ];

	protected $casts = [
        'garage_id' => 'int',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

	public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
	
}