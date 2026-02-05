<?php

namespace App\Http\Controllers;

use App\Repositories\VehicleType\VehicleTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
    private $vehicleTypeRepo;

    public function __construct(VehicleTypeRepository $vehicleTypeRepository)
    {
        $this->vehicleTypeRepo = $vehicleTypeRepository;
    }

	/**
     * Get paginated list of Vehicle Type.
     *
     * @param Request $request
     * @return object Paginated Vehicle Type list with total count
     */
	public function getVehicleTypes(Request $request)
    {
        return $this->vehicleTypeRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc']);
    }

	/**
     * Create a new Vehicle type.
     *
     * @param Request $request
     */
	public function addVehicleType(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|unique:aus_vehicle_types,type',
        ])->validate();

        return $this->vehicleTypeRepo->create($request['type']);
    }

	/**
     * Edit a Vehicle Type.
     *
     * @param Request $request
     */
    public function editVehicleType(Request $request)
    {
        $vehicleType = $this->vehicleTypeRepo->getById($request['id']);

        Validator::make($request->all(), [
            'type' => 'required|unique:aus_vehicle_types,type',
        ])->validate();

        return $this->vehicleTypeRepo->edit($vehicleType, $request['type']);
    }

	/**
     * Delete Vehicle Type.
     *
     * @param Request $request
     */
    public function deleteVehicleType(Request $request)
    {
        return $this->vehicleTypeRepo->delete($request['id']);
    }
}
