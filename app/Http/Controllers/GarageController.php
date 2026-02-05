<?php

namespace App\Http\Controllers;

use App\Repositories\Garage\GarageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Garage;
use Illuminate\Validation\Rule;

class GarageController extends Controller
{
    private $garageRepo;

    public function __construct(GarageRepository $garageRepository)
    {
        $this->garageRepo = $garageRepository;
    }

	/**
     * Get paginated list of garage.
     *
     * @param Request $request
     * @return object Paginated garage list with total count
     */
	public function getGarages(Request $request)
    {
        return $this->garageRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc']);
    }

	/**
     * Create a new garage.
     *
     * @param Request $request
     */
	public function addGarage(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:aus_garages,name',
            'address' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        return $this->garageRepo->create($request['name'], $request['description'], $request['address'], $request['technician_id'], 
			$request['service_ids'], $request['type_ids'], $request['brand_ids'], $request['modele_ids']);
    }
	
	/**
     * Edit a garage.
     *
     * @param Request $request
     */
    public function editGarage(Request $request)
    {
        $garage = $this->garageRepo->getById($request['id'], ['garageServices']);

        Validator::make($request->all(), [
            'name' => [
				'required',
				Rule::unique('aus_garages')->ignore($request->id),
			],
            'address' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        return $this->garageRepo->edit($garage, $request['name'], $request['description'], $request['address'], $request['technician_id'], 
			$request['service_ids'], $request['type_ids'], $request['brand_ids'], $request['modele_ids']);
    }

	/**
     * Delete garage.
     *
     * @param Request $request
     */
    public function deleteGarage(Request $request)
    {
        return $this->garageRepo->delete($request['id']);
    }

	/**
     * Fin garage by technician.
     *
     * @param Request $request
     */
    public function getGarageByTechnician(Request $request)
    {
        $technician_id = $request->user()->id;
        return $this->garageRepo->getGarageByTechnician($technician_id);
    }

	/**
     * Update garage info.
     *
     * @param Request $request
     */
    public function updateGarageInfo(Request $request)
    {
        $technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);

        Validator::make($request->all(), [
            'name' => [
				'required',
				Rule::unique('aus_garages')->ignore($garage->id),
			],
            'address' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        return $this->garageRepo->updateGarageInfo($garage, $request['name'], $request['description'], $request['address']);
    }

	/**
     * Find garages by service.
     *
     * @param Request $request
     */
    public function getGaragesByService(Request $request)
    {
        return $this->garageRepo->getGaragesByService($request['service_id']);
    }

}
