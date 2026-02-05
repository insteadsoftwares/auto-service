<?php

namespace App\Repositories\Service;

interface ServiceRepository
{
    
	/**
     * paginates Service
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @return Service[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc);
	
    /**
     * Creates a service
     *
     * @param $name: string
     * @param $description: string
     * @param $image: string
     * @param $imageName: string
     * @return Service
     */
    public function create($name, $description, $image, $imageName);

    /**
     * Finds service by id.
     *
     * @return Service
     */
    public function getById($id);

	/**
     * Edit a service
     *
     * @param $service: service
     * @param $name: string
     * @param $description: string
     * @param $image: string
     * @param $imageName: string
     * @return Service
     */
	public function edit($service, $name, $description, $image, $imageName);

    /**
     * Deletes service
     *
     * @param $service_id: int
     */
    public function delete($service_id); 

	/**
     * Find services number.
     *
	 * @return int
     */
    public function getServicesNumber();

	/**
     * Find three best services.
     *
	 * @return Object
     */
    public function getThreeBestServices();
	
}
