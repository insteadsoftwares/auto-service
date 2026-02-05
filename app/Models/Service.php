<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
	use SoftDeletes;

    protected $table = 'aus_services';

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

	protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'image' => 'string',
    ];

	public function appointments()
    {
        return $this->hasMany(Appointment::class, 'service_id');
    }
}
