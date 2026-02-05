<?php

namespace App\Repositories\ClientVehicle;

use App\Models\ClientVehicle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentClientVehicle implements ClientVehicleRepository
{
    /**
     * @var ClientVehicle
     */
    private $model;

    public function __construct(ClientVehicle $model)
    {
        $this->model = $model;
    }

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
    public function create($client_id, $type_id, $brand_id, $modele_id, $registration_number, $year, $description)
    {
        $createdClientVehicle = ClientVehicle::create([
            'client_id' => $client_id,
            'type_id' => $type_id,
            'brand_id' => $brand_id,
            'modele_id' => $modele_id,
            'registration_number' => $registration_number,
            'year' => $year,
            'description' => $description,
        ]);

        return $createdClientVehicle;
    }

	/**
     * Finds Client Vehicle by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return ClientVehicle
     */
    public function getById($id, $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

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
	public function edit($client_vehicle, $type_id, $brand_id, $modele_id, $registration_number, $year, $description)
    {
		$client_vehicle->type_id = $type_id;
		$client_vehicle->brand_id = $brand_id;
		$client_vehicle->modele_id = $modele_id;
		$client_vehicle->registration_number = $registration_number;
		$client_vehicle->year = $year;
		$client_vehicle->description = $description;
		$client_vehicle->save();

		return $client_vehicle;
    }

	/**
     * Deletes Client Vehicle
     *
     * @param $client_vehicle_id: int
     */
    public function delete($client_vehicle_id)
    {
        $clientVehicle = $this->getById($client_vehicle_id);
        return $clientVehicle->delete();
    }

	/**
     * Finds Vehicles by Client.
     *
     * @param $client_id: int
     * @param $relations: array
     * @return ClientVehicle
     */
    public function getVehiclesByClient($client_id, $relations = [])
    {
        return $this->model::where('client_id', $client_id)->with($relations)->get();
    }
}
