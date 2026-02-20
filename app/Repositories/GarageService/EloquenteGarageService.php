<?php

namespace App\Repositories\GarageService;

use App\Models\GarageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquenteGarageService implements GarageServiceRepository
{
    /**
     * @var GarageService
     */
    private $model;

    public function __construct(GarageService $model)
    {
        $this->model = $model;
    }

	/**
     * Finds by id service and technician.
     *
     * @param $service_id: int
     * @param $technician_id: int
     * @return GarageService
     */
    public function getByServiceAndTechnician($service_id, $technician_id)
    {
        return $this->model::where('service_id', $service_id)
				->whereHas('garage', function ($query) use ($technician_id) {
					$query->where('technician_id', $technician_id);
				})
				->first();
    }

	/**
     * Deletes Garage Service
     *
     * @param $garage_service: GarageService
     */
    public function delete($garage_service)
    {
        return $garage_service->delete();
    }

	/**
     * Create Garage Service
     *
     * @param $service_id: int
     * @param $garage_id: int
	 * @return GarageService
     */
    public function createGarageServices($service_id, $garage_id)
    {
        $garageService = GarageService::create([
				'garage_id' => $garage_id,
				'service_id' => $service_id,
			]);

		return $garageService->load('service');
    }

}
