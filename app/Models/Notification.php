<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'aus_notifications';

    protected $fillable = [
        'appointment_id',
        'title',
        'message',
        'is_read',
        'update_user_id',
    ];

	protected $casts = [
        'appointment_id' => 'int',
        'title' => 'string',
        'message' => 'string',
        'is_read' => 'boolean',
        'update_user_id' => 'int',
    ];

	public function user()
    {
        return $this->belongsTo(User::class, 'update_user_id');
    }

	public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
	
}
