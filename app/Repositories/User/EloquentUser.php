<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Repositories\Garage\GarageRepository;

class EloquentUser implements UserRepository
{
    /**
     * @var User
     */
    private $model;
	private $garageRepo;

    public function __construct(User $model, GarageRepository $garageRepository)
    {
        $this->model = $model;
		$this->garageRepo = $garageRepository;
    }

	/**
     * paginates User by role
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @param $role: string
     * @return User[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $role)
	{
		$query = $this->model->newQuery();

		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('first_name', 'LIKE', '%' . $searchQuery . '%')
				->orWhere('last_name', 'LIKE', '%' . $searchQuery . '%');
			});
		}

		$query->whereHas('role', function($q) use ($role) {
			$q->where('name', $role);
		});
		

		$total = $query->count();

		$nodes = $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc')
					->skip(($page - 1) * $perPage)
					->take($perPage)
					->get();

		return (object) [
			'nodes' => $nodes,
			'total' => $total,
		];
	}

	/**
     * Get technicians (users with role "technician") who do not have a garage
     *
     * @return User[]
     */
	public function getTechniciansWithoutGarage()
    {
        return User::techniciansWithoutGarage()->get();
    }

    /**
     * Finds user by id.
     *
     * @return User
     */
    public function getById($id, $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

	/**
     * edit a user
     *
     * @param $user: User
     * @param $first_name: string
     * @param $last_name: string
     * @param $address: string
     * @param $phone_number: string
     * @param $email: string
     * @param $password: string
     * @return Service
     */
	public function edit($user, $first_name, $last_name, $address, $phone_number, $email, $password)
    {
		$user->first_name = $first_name;
		$user->last_name = $last_name;
		$user->address = $address;
		$user->phone_number = $phone_number;
		$user->email = $email;
		if($password != null) $user->password = $password;
		$user->save();

		return $user;
    }

	/**
     * Deletes user
     *
     * @param $user_id: int
     */
    public function delete($user_id)
    {
        $user = $this->getById($user_id, ['role']);
		if($user->role->name == 'technician'){
			if($user->garage){
				$this->garageRepo->delete($user->garage->id);
			}
		}
        return $user->delete();
    }
	
	/**
     * Find clients number.
     *
	 * @return int
     */
    public function getCLientNumber()
    {
        return $this->model::whereHas('role', function ($q) {
				$q->where('name', 'client');
			})->where('deleted_at', null)->count();
    }
	
}
