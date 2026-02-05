<?php

namespace App\Repositories\VehicleType;

interface VehicleTypeRepository
{

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
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc);

    /**
     * Creates a Vehicle type
     *
     * @param $type: string
     * @return VehicleType
     */
    public function create($type);

	
	/**
     * Finds vehicle type by id.
     *
     * @return VehicleType
     */
    public function getById($id);
	

	/**
     * Edit a vehicle type
     *
     * @param $vehicleType: VehicleType
     * @param $type: string
     * @return VehicleType
     */
	public function edit($vehicleType, $type);
	
    /**
     * Deletes vehicle type
     *
     * @param $vehicle_type_id: int
     */
    public function delete($vehicle_type_id);

}
