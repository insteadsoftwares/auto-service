<?php

namespace App\Repositories\Garage;

interface GarageRepository
{

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
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc);

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
    public function create($name, $description, $address, $technician_id, $service_ids, $type_ids, $brand_ids, $modele_ids);
	
	/**
     * Finds garage by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return Garage
     */
    public function getById($id, $relations = []);

	/**
     * Add garage Specialt.
     *
     * @param $garage_id: int
     * @param $type_ids: array
     * @param $brand_ids: array
     * @param $modele_ids: array
     */
    public function addGarageSpecialties($garage_id, $type_ids, $brand_ids, $modele_ids);

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
	public function edit($garage, $name, $description, $address, $technician_id, $service_ids, $type_ids, $brand_ids, $modele_ids);
		
	/**
     * Deletes garage
     *
     * @param $garage_id: int
     */
    public function delete($garage_id);

	/**
     * Fin garage by technician.
     *
     * @param $technician_id: int
     */
    public function getGarageByTechnician($technician_id);

	/**
     * Update garage info.
     *
     * @param $garage: Garage
     * @param $name: string
     * @param $description: string
     * @param $address: string
     */
    public function updateGarageInfo($garage, $name, $description, $address);
	
	/**
     * Find garages by service.
     *
     * @param $service_id: string
     */
    public function getGaragesByService($service_id);

	/**
     * Find garages number.
     *
	 * @return int
     */
    public function getGaragesNumber();

	/**
     * Find six best garages.
     *
     * @param $service_id: int
	 * @return Object
     */
    public function getSixBestGarages($service_id);

}
