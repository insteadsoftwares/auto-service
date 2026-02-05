<?php

namespace App\Repositories\VehicleType;

use App\Models\VehicleType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentVehicleType implements VehicleTypeRepository
{
    /**
     * @var VehicleType
     */
    private $model;

    public function __construct(VehicleType $model)
    {
        $this->model = $model;
    }

	/**
     * paginates Vehicle Type
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @return VehicleType[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc)
	{
		$query = $this->model->newQuery();

		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('type', 'LIKE', '%' . $searchQuery . '%');
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
     * Creates a Vehicle type
     *
     * @param $type: string
     * @return VehicleType
     */
    public function create($type)
    {
        $createdVehicleType = VehicleType::create([
            'type' => $type,
        ]);

        return $createdVehicleType;
    }
	
	/**
     * Finds vehicle type by id.
     *
     * @return VehicleType
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

	/**
     * Edit a vehicle type
     *
     * @param $vehicleType: VehicleType
     * @param $type: string
     * @return VehicleType
     */
	public function edit($vehicleType, $type)
    {
		$vehicleType->type = $type;
		$vehicleType->save();

		return $vehicleType;
    }

    /**
     * Deletes vehicle type
     *
     * @param $vehicle_type_id: int
     */
    public function delete($vehicle_type_id)
    {
        $vehicleType = $this->getById($vehicle_type_id);
        return $vehicleType->delete();
    }

}
