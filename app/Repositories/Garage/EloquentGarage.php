<?php

namespace App\Repositories\Garage;

use App\Models\Garage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\GarageService;
use App\Models\VehicleModele;
use App\Models\GarageSpecialty;

class EloquentGarage implements GarageRepository
{
    /**
     * @var Garage
     */
    private $model;

    public function __construct(Garage $model)
    {
        $this->model = $model;
    }

	/**
     * paginates garage
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @return Garage[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc)
	{
		$query = $this->model->newQuery();
		$query->with(['technician', 'garageServices.service', 'garageSpecialties.vehicleType', 'garageSpecialties.vehicleBrand', 'garageSpecialties.vehicleModele']); 
		if (!empty($searchQuery)) {
			$query->where(function($q) use ($searchQuery) {
				$q->where('name', 'LIKE', '%' . $searchQuery . '%');
				$q->orWhere('address', 'LIKE', '%' . $searchQuery . '%');
				$q->orWhereHas('technician', function($q2) use ($searchQuery) {
					$q2->where('first_name', 'LIKE', '%' . $searchQuery . '%')
					->orWhere('last_name', 'LIKE', '%' . $searchQuery . '%');
				});
			});
		}

		$total = $query->count();

		if (!empty($sortBy)) {
			$query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
		}

		if (!is_null($page) && !is_null($perPage)) {
			$query->skip(($page - 1) * $perPage)->take($perPage);
		}

		return (object) [
			'nodes' => $query->get(),
			'total' => $total,
		];
	}

    /**
     * Creates a garage
     *
     * @param $name: string
     * @param $description: string
     * @param $address: string
     * @param $technician_id: int
     * @param $service_ids: array
     * @param $type_ids: array
     * @param $brand_ids: array
     * @param $modele_ids: array
     * @return Garage
     */
    public function create($name, $description, $address, $technician_id, $service_ids, $type_ids, $brand_ids, $modele_ids)
    {
        $createdGarage = Garage::create([
            'name' => $name,
            'description' => $description,
            'address' => $address,
            'technician_id' => $technician_id,
        ]);

		foreach ($service_ids as $service_id) {
			GarageService::create([
				'garage_id' => $createdGarage->id,
				'service_id' => $service_id,
			]);
		}

		$this->addGarageSpecialties($createdGarage->id, $type_ids, $brand_ids, $modele_ids);

        return $createdGarage;
    }
	
	/**
     * Add garage Specialt.
     *
     * @param $garage_id: int
     * @param $type_ids: array
     * @param $brand_ids: array
     * @param $modele_ids: array
     */
    public function addGarageSpecialties($garage_id, $type_ids, $brand_ids, $modele_ids)
    {
        foreach ($modele_ids as $modele_id) {
			$modele = VehicleModele::find($modele_id);
			GarageSpecialty::create([
				'garage_id' => $garage_id,
				'vehicle_type_id' => $modele->type_id,
				'vehicle_brand_id' => $modele->brand_id,
				'vehicle_modele_id' => $modele->id,
			]);
		}

		foreach ($type_ids as $type_id) {
			$exists = GarageSpecialty::where('garage_id', $garage_id)
				->where('vehicle_type_id', $type_id)
				->whereNotNull('vehicle_modele_id')
				->exists();

			if (!$exists) {
				GarageSpecialty::create([
					'garage_id' => $garage_id,
					'vehicle_type_id' => $type_id,
					'vehicle_brand_id' => null,
					'vehicle_modele_id' => null,
				]);
			}
		}

		foreach ($brand_ids as $brand_id) {
			$exists = GarageSpecialty::where('garage_id', $garage_id)
				->where('vehicle_brand_id', $brand_id)
				->whereNotNull('vehicle_modele_id')
				->exists();

			if (!$exists) {
				GarageSpecialty::create([
					'garage_id' => $garage_id,
					'vehicle_type_id' => null,
					'vehicle_brand_id' => $brand_id,
					'vehicle_modele_id' => null,
				]);
			}
		}
    }

	/**
     * Finds garage by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return Garage
     */
    public function getById($id, $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

	/**
     * Edit a garage
     *
     * @param $garage: Garage
     * @param $name: string
     * @param $description: string
     * @param $address: string
     * @param $technician_id: int
     * @param $service_ids: array
     * @param $type_ids: array
     * @param $brand_ids: array
     * @param $modele_ids: array
     * @return Garage
     */
	public function edit($garage, $name, $description, $address, $technician_id, $service_ids, $type_ids, $brand_ids, $modele_ids)
    {
		$garage->name = $name;
		$garage->description = $description;
		$garage->address = $address;
		$garage->technician_id = $technician_id;
		$garage->save();

		$existingServices = $garage->garageServices()->pluck('service_id')->toArray();
		$servicesToDelete = array_diff($existingServices, $service_ids);
		$servicesToAdd = array_diff($service_ids, $existingServices);

		if (!empty($servicesToDelete)) {
			GarageService::where('garage_id', $garage->id)->whereIn('service_id', $servicesToDelete)->delete();
		}

		foreach ($servicesToAdd as $service_id) {
			GarageService::create([
				'garage_id' => $garage->id,
				'service_id' => $service_id,
			]);
		}

		GarageSpecialty::where('garage_id', $garage->id)->delete();
		$this->addGarageSpecialties($garage->id, $type_ids, $brand_ids, $modele_ids);

		return $garage;
    }

	/**
     * Deletes garage
     *
     * @param $garage_id: int
     */
    public function delete($garage_id)
    {
        $garage = $this->getById($garage_id);
        return $garage->delete();
    }

	/**
     * Fin garage by technician.
     *
     * @param $technician_id: int
     */
    public function getGarageByTechnician($technician_id)
    {
        return $this->model::where('technician_id', $technician_id)->with(['garageServices.service', 'garageSpecialties.vehicleType', 'garageSpecialties.vehicleBrand', 'garageSpecialties.vehicleModele'])->first();
    }

	/**
     * Update garage info.
     *
     * @param $garage: Garage
     * @param $name: string
     * @param $description: string
     * @param $address: string
     */
    public function updateGarageInfo($garage, $name, $description, $address)
    {
        $garage->name = $name;
		$garage->description = $description;
		$garage->address = $address;
		$garage->save();
		return $garage;
    }

	/**
     * Find garages by service.
     *
     * @param $service_id: string
     */
    public function getGaragesByService($service_id)
    {
        return $this->model::whereHas('garageServices', function ($query) use ($service_id) {
            $query->where('service_id', $service_id);
        })
        ->get();
    }

	/**
     * Find garages number.
     *
	 * @return int
     */
    public function getGaragesNumber()
    {
        return $this->model::where('deleted_at', null)->count();
    }

	/**
     * Find six best garages.
     *
     * @param $service_id: int
	 * @return Object
     */
    public function getSixBestGarages($service_id)
    {
        $topGarages = $this->model::withCount([
			'appointments as appointments_count' => function ($query) use ($service_id) {
				if ($service_id !== 0) {
					$query->where('service_id', $service_id);
				}
			}
		])
		->when($service_id !== 0, function ($query) use ($service_id) {
			$query->whereHas('appointments', function ($q) use ($service_id) {
				$q->where('service_id', $service_id);
			});
		})
		->orderByDesc('appointments_count')
		->take(6)
		->get();

		return $topGarages->map(function ($garage) {
			return [
				'garage' => $garage,
				'appointments_nb' => $garage->appointments_count,
			];
		});
    }
}
