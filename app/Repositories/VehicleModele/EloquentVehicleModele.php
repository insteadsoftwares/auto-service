<?php

namespace App\Repositories\VehicleModele;

use App\Models\VehicleModele;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentVehicleModele implements VehicleModeleRepository
{
    /**
     * @var VehicleModele
     */
    private $model;

    public function __construct(VehicleModele $model)
    {
        $this->model = $model;
    }

	/**
     * paginates Vehicle Modele
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @param $active: boolean
     * @return VehicleModele[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $active = null)
	{
		$query = $this->model->newQuery();
		
		$query->with(['brand', 'type']);
		if ($active !== null) $query->where('active', 1);
		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('modele', 'LIKE', '%' . $searchQuery . '%')
				->orWhereHas('brand', function($query) use ($searchQuery) {
					$query->where('name', 'LIKE', '%' . $searchQuery . '%');
				});
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
     * Creates a Vehicle Modele
     *
     * @param $modele: string
     * @param $brand_id: int
     * @param $type_id: int
     * @param $active: boolean
     * @return VehicleModele
     */
    public function create($modele, $brand_id, $type_id, $active)
    {
        $createdVehicleModele = VehicleModele::create([
            'modele' => $modele,
            'brand_id' => $brand_id,
            'type_id' => $type_id,
            'active' => $active,
        ]);

        return $createdVehicleModele;
    }
	
	/**
     * Finds vehicle modele by id.
     *
     * @return VehicleModele
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

	/**
     * Edit a vehicle modele
     *
     * @param $vehicleModele: VehicleModele
     * @param $modele: string
     * @param $brand_id: int
     * @param $type_id: int
     * @param $active: boolean
     * @return VehicleModele
     */
	public function edit($vehicleModele, $modele, $brand_id, $type_id, $active)
    {
		$vehicleModele->modele = $modele;
		$vehicleModele->brand_id = $brand_id;
		$vehicleModele->type_id = $type_id;
		$vehicleModele->active = $active;
		$vehicleModele->save();

		return $vehicleModele;
    }

}
