<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarageService extends Model
{
	use SoftDeletes;

    protected $table = 'aus_garage_services';

    protected $fillable = [
        'garage_id',
        'service_id',
    ];

	protected $casts = [
        'garage_id' => 'int',
        'service_id' => 'int',
    ];

	public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
	
	public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
