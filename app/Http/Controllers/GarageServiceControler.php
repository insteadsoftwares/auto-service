<?php

namespace App\Http\Controllers;

use App\Repositories\GarageService\GarageServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GarageService;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Repositories\Garage\GarageRepository;

class GarageServiceControler extends Controller
{
    private $garageServiceRepo;
    private $garageRepo;

    public function __construct(GarageServiceRepository $garageServiceRepository, GarageRepository $garageRepository)
    {
        $this->garageServiceRepo = $garageServiceRepository;
        $this->garageRepo = $garageRepository;
    }
	
	/**
     * delete service.
     *
     * @param Request $request
     */
    public function deleteGarageService(Request $request)
    {
		$technician_id = $request->user()->id;
		$garageService = $this->garageServiceRepo->getByServiceAndTechnician($request['id'], $technician_id);
		if($garageService) return $this->garageServiceRepo->delete($garageService);
    }
	
	/**
     * add service.
     *
     * @param Request $request
     */
    public function addGarageServices(Request $request)
    {
		Validator::make($request->all(), [
            'service_ids' => 'required|array',
        ])->validate();

		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		$createdServices = [];
		foreach ($request->service_ids as $service_id) {
			$createdServices[] = $this->garageServiceRepo->createGarageServices($service_id, $garage->id);
		}
		return $createdServices;
    }

}
