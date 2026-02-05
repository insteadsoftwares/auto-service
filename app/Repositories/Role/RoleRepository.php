<?php

namespace App\Repositories\Role;

interface RoleRepository
{

    /**
     * Find role by name.
     *
     * @param $name: string
     * @return Role
     */
	public function getRoleByName($name);

}
