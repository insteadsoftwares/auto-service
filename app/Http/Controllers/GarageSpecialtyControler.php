<?php

namespace App\Http\Controllers;

use App\Repositories\GarageSpecialty\GarageSpecialtyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GarageSpecialty;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Repositories\Garage\GarageRepository;

class GarageSpecialtyControler extends Controller
{
    private $garageSpecialtyRepo;
    private $garageRepo;

    public function __construct(GarageSpecialtyRepository $garageSpecialtyRepository, GarageRepository $garageRepository)
    {
        $this->garageSpecialtyRepo = $garageSpecialtyRepository;
        $this->garageRepo = $garageRepository;
    }
	
	/**
     * delete Specialty.
     *
     * @param Request $request
     */
    public function deleteGarageSpecialty(Request $request)
    {
		$technician_id = $request->user()->id;
		$garageSpecialty = $this->garageSpecialtyRepo->getById($request['id'], ['garage']);
		if($garageSpecialty->garage->technician_id == $technician_id) return $this->garageSpecialtyRepo->delete($garageSpecialty);
    }

	/**
     * add service.
     *
     * @param Request $request
     */
    public function addGarageSpecialties(Request $request)
    {
		Validator::make($request->all(), [
            'type_ids' => 'array',
            'brand_ids' => 'array',
            'modele_ids' => 'required|array',
        ])->validate();

		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		return $this->garageRepo->addGarageSpecialties($garage->id, $request->type_ids, $request->brand_ids, $request->modele_ids);
    }
}
