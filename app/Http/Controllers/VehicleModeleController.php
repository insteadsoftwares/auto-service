<?php

namespace App\Http\Controllers;

use App\Repositories\VehicleModele\VehicleModeleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleModele;
use Illuminate\Validation\Rule;

class VehicleModeleController extends Controller
{
    private $vehicleModeleRepo;

    public function __construct(VehicleModeleRepository $vehicleModeleRepository)
    {
        $this->vehicleModeleRepo = $vehicleModeleRepository;
    }

	/**
     * Get paginated list of Vehicle Modele.
     *
     * @param Request $request
     * @return object Paginated Vehicle Modele list with total count
     */
	public function getVehicleModeles(Request $request)
    {
        return $this->vehicleModeleRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc']);
    }

	/**
     * Create a new Vehicle Modele.
     *
     * @param Request $request
     */
	public function addVehicleModele(Request $request)
    {
        Validator::make($request->all(), [
            'modele' => 'required|unique:aus_vehicle_modeles,modele,NULL,id,brand_id,' . $request->brand_id,
    		'brand_id' => 'required',
    		'type_id' => 'required',
            'active' => 'required|boolean',
        ])->validate();

        return $this->vehicleModeleRepo->create($request['modele'], $request['brand_id'], $request['type_id'], $request['active']);
    }

	/**
     * Edit a Vehicle modele.
     *
     * @param Request $request
     */
    public function editVehicleModele(Request $request)
    {
        $vehicleModele = $this->vehicleModeleRepo->getById($request['id']);

        Validator::make($request->all(), [
            'modele' => [
				'required',
				Rule::unique('aus_vehicle_modeles')->where(function ($query) use ($request) {
					return $query->where('brand_id', $request->brand_id);
				})->ignore($request->id), 
			],
    		'brand_id' => 'required',
    		'type_id' => 'required',
            'active' => 'required|boolean',
        ])->validate();

        return $this->vehicleModeleRepo->edit($vehicleModele, $request['modele'], $request['brand_id'], $request['type_id'], $request['active']);
    }
}
