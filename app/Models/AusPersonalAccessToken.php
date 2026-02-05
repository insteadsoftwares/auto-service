<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumToken;

class AusPersonalAccessToken extends SanctumToken
{
    protected $table = 'aus_personal_access_tokens';
}
