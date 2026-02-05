<?php

namespace App\Http\Controllers;

use App\Repositories\ClientVehicle\ClientVehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ClientVehicle;
use Illuminate\Validation\Rule;

class ClientVehicleController extends Controller
{
    private $clientVehicleRepo;

    public function __construct(ClientVehicleRepository $clientVehicleRepository)
    {
        $this->clientVehicleRepo = $clientVehicleRepository;
    }

	/**
     * Get Client Vehicle.
     *
     * @param Request $request
     * @return ClientVehicle
     */
	public function getClientVehicles(Request $request)
    {
		$client_id = $request->user()->id;
        return $this->clientVehicleRepo->getVehiclesByClient($client_id, ['vehicleType', 'vehicleBrand', 'vehicleModele']);
    }

	/**
     * Create a new Client Vehicle.
     *
     * @param Request $request
     */
	public function addClientVehicle(Request $request)
    {
        Validator::make($request->all(), [
            'type_id' => 'required|int',
        ])->validate();
		
		$client_id = $request->user()->id;
        return $this->clientVehicleRepo->create($client_id, $request['type_id'], $request['brand_id'], $request['modele_id'], 
			$request['registration_number'], $request['year'], $request['description']);
    }
	
	/**
     * Edit a Client Vehicle.
     *
     * @param Request $request
     */
    public function editClientVehicle(Request $request)
    {
        $client_vehicle = $this->clientVehicleRepo->getById($request['id']);

        Validator::make($request->all(), [
            'type_id' => 'required|int',
        ])->validate();

        return $this->clientVehicleRepo->edit($client_vehicle, $request['type_id'], $request['brand_id'], $request['modele_id'], 
			$request['registration_number'], $request['year'], $request['description']);
    }

	/**
     * Delete Client Vehicle.
     *
     * @param Request $request
     */
    public function deleteClientVehicle(Request $request)
    {
        return $this->clientVehicleRepo->delete($request['id']);
    }

}
