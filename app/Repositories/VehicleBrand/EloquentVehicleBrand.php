<?php

namespace App\Repositories\VehicleBrand;

use App\Models\VehicleBrand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentVehicleBrand implements VehicleBrandRepository
{
    /**
     * @var VehicleBrand
     */
    private $model;

    public function __construct(VehicleBrand $model)
    {
        $this->model = $model;
    }

	/**
     * paginates Vehicle Brand
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @param $active: boolean
     * @return VehicleBrand[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $active)
	{
		$query = $this->model->newQuery();

		if ($active !== null) $query->where('active', $active);
		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('name', 'LIKE', '%' . $searchQuery . '%');
			});
		}

		$total = $query->count();
		if ($sortBy !== '' && $sortBy !== null) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }
        if ($page !== null && $perPage !== null) {
            $query->skip(($page - 1) * $perPage)->take($perPage);
        }

		return (object) [
            'nodes' => $query->get(),
            'total' => $total,
        ];
	}

    /**
     * Creates a vehicle brand
     *
     * @param $name: string
     * @param $active: boolean
     * @return VehicleBrand
     */
    public function create($name, $active)
    {
        $createdVehicleBrand = VehicleBrand::create([
            'name' => $name,
            'active' => $active,
        ]);

        return $createdVehicleBrand;
    }

	/**
     * Finds vehicle brand by id.
     *
     * @return VehicleBrand
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

	/**
     * Edit a vehicle brand
     *
     * @param $vehicleBrand: VehicleBrand
     * @param $name: string
     * @param $active: boolean
     * @return VehicleBrand
     */
	public function edit($vehicleBrand, $name, $active)
    {
		$vehicleBrand->name = $name;
		$vehicleBrand->active = $active;
		$vehicleBrand->save();

		return $vehicleBrand;
    }

}
