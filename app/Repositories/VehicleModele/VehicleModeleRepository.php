<?php

namespace App\Repositories\VehicleModele;

interface VehicleModeleRepository
{

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
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc, $active = null);

    /**
     * Creates a Vehicle Modele
     *
     * @param $modele: string
     * @param $brand_id: int
     * @param $type_id: int
     * @param $active: boolean
     * @return VehicleModele
     */
    public function create($modele, $brand_id, $type_id, $active);
	
	/**
     * Finds vehicle modele by id.
     *
     * @return VehicleModele
     */
    public function getById($id);

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
	public function edit($vehicleModele, $modele, $brand_id, $type_id, $active);
	
}
