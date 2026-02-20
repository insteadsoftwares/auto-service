<?php

namespace App\Repositories\GarageService;

interface GarageServiceRepository
{

	/**
     * Finds by id service and technician.
     *
     * @param $service_id: int
     * @param $technician_id: int
     * @return GarageService
     */
    public function getByServiceAndTechnician($service_id, $technician_id);

	/**
     * Deletes Garage Service
     *
     * @param $garage_service: GarageService
     */
    public function delete($garage_service);

	/**
     * Create Garage Service
     *
     * @param $service_id: int
     * @param $garage_id: int
	 * @return GarageService
     */
    public function createGarageServices($service_id, $garage_id);

}
