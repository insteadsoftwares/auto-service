<?php

namespace App\Http\Controllers;

use App\Repositories\VehicleBrand\VehicleBrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleBrand;
use Illuminate\Validation\Rule;

class VehicleBrandController extends Controller
{
    private $vehicleBrandRepo;

    public function __construct(VehicleBrandRepository $vehicleBrandRepository)
    {
        $this->vehicleBrandRepo = $vehicleBrandRepository;
    }

	/**
     * Get paginated list of Vehicle Brand.
     *
     * @param Request $request
     * @return object Paginated Vehicle Brand list with total count
     */
	public function getVehicleBrands(Request $request)
    {
        return $this->vehicleBrandRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc'], $request['active']);
    }

	/**
     * Create a new Vehicle Brand.
     *
     * @param Request $request
     */
	public function addVehicleBrand(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:aus_vehicle_brands,name',
            'active' => 'required|boolean',
        ])->validate();

        return $this->vehicleBrandRepo->create($request['name'], $request['active']);
    }
	
	/**
     * Edit a Vehicle Brand.
     *
     * @param Request $request
     */
    public function editVehicleBrand(Request $request)
    {
        $vehicleBrand = $this->vehicleBrandRepo->getById($request['id']);

        Validator::make($request->all(), [
            'name' => [
				'required',
				Rule::unique('aus_vehicle_brands')->ignore($request->id),
			],
            'active' => 'required|boolean',
        ])->validate();

        return $this->vehicleBrandRepo->edit($vehicleBrand, $request['name'], $request['active']);
    }

}
