<?php

namespace App\Http\Controllers;

use App\Repositories\GarageLeave\GarageLeaveRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GarageLeave;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Repositories\Garage\GarageRepository;

class GarageLeaveController extends Controller
{
    private $garageLeaveRepo;
    private $garageRepo;

    public function __construct(GarageLeaveRepository $garageLeaveRepository, GarageRepository $garageRepository)
    {
        $this->garageLeaveRepo = $garageLeaveRepository;
        $this->garageRepo = $garageRepository;
    }
	
	/**
     * add Leave.
     *
     * @param Request $request
     */
    public function addGarageLeaves(Request $request)
    {
		Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ])->validate();

		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		return $this->garageLeaveRepo->createGarageLeaves($garage->id, $request['start_date'], $request['end_date'], $technician_id);
    }
	
	/**
     * delete Leave.
     *
     * @param Request $request
     */
    public function deleteGarageLeave(Request $request)
    {
		Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ])->validate();

		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		return $this->garageLeaveRepo->removeLeaveDayS($garage->id, $request['start_date'], $request['end_date']);
    }
	
	/**
     * Find garage Leaves.
     *
     * @param Request $request
     */
    public function getGarageLeaves(Request $request)
    {
		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		return $this->garageLeaveRepo->getGarageLeaves($garage->id);
    }

}
