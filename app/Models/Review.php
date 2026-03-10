<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
	use SoftDeletes;

    protected $table = 'aus_reviews';

    protected $fillable = [
        'client_id',
        'appointment_id',
        'rating',
        'comment',
    ];

	protected $casts = [
        'client_id' => 'int',
        'appointment_id' => 'int',
        'rating' => 'int',
        'comment' => 'string',
    ];

	public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

	public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
	
}
