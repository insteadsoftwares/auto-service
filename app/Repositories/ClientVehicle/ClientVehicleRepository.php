<?php

namespace App\Repositories\ClientVehicle;

interface ClientVehicleRepository
{

    /**
     * Creates a Client Vehicle
     *
     * @param $client_id: int
     * @param $type_id: int
     * @param $brand_id: int
     * @param $modele_id: int
     * @param $registration_number: string
     * @param $year: int
     * @param $description: string
     * @return ClientVehicle
     */
    public function create($client_id, $type_id, $brand_id, $modele_id, $registration_number, $year, $description);
	
	/**
     * Finds Client Vehicle by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return ClientVehicle
     */
    public function getById($id, $relations = []);

	/**
     * Edit a Client Vehicle
     *
     * @param $client_vehicle: ClientVehicle
     * @param $type_id: int
     * @param $brand_id: int
     * @param $modele_id: int
     * @param $registration_number: string
     * @param $year: int
     * @param $description: string
     * @return ClientVehicle
     */
	public function edit($client_vehicle, $type_id, $brand_id, $modele_id, $registration_number, $year, $description);
		
	/**
     * Deletes Client Vehicle
     *
     * @param $client_vehicle_id: int
     */
    public function delete($client_vehicle_id);
	
	/**
     * Finds Vehicles by Client.
     *
     * @param $client_id: int
     * @param $relations: array
     * @return ClientVehicle
     */
    public function getVehiclesByClient($client_id, $relations = []);

}
