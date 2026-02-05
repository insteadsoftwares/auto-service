<?php

namespace App\Repositories\Role;

use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentRole implements RoleRepository
{
    /**
     * @var Role
     */
    private $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * Find role by name.
     *
     * @param $name: string
     * @return Role
     */
	public function getRoleByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

}
