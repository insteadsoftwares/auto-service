<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\AusPersonalAccessToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'aus_users';

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'email',
        'google_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

	public function tokens()
    {
        return $this->morphMany(AusPersonalAccessToken::class, 'tokenable');
    }

	public function garage()
	{
		return $this->hasOne(Garage::class, 'technician_id');
	}

    public function scopeTechniciansWithoutGarage(Builder $query)
    {
		return $query
            ->whereHas('role', function ($q) {
                $q->where('name', 'technician');
            })
            ->whereDoesntHave('garage');
    }
}
