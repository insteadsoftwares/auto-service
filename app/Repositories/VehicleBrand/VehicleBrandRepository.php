<?php

namespace App\Repositories\VehicleBrand;

interface VehicleBrandRepository
{

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
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $active);

    /**
     * Creates a Vehicle brand
     *
     * @param $name: string
     * @param $active: boolean
     * @return VehicleBrand
     */
    public function create($name, $active);
	
	/**
     * Finds vehicle brand by id.
     *
     * @return VehicleBrand
     */
    public function getById($id);

	/**
     * Edit a vehicle brand
     *
     * @param $vehicleBrand: VehicleBrand
     * @param $name: string
     * @param $active: boolean
     * @return VehicleBrand
     */
	public function edit($vehicleBrand, $name, $active);
}
