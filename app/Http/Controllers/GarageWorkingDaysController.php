<?php

namespace App\Http\Controllers;

use App\Repositories\GarageWorkingDays\GarageWorkingDaysRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GarageLeave;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Repositories\Garage\GarageRepository;

class GarageWorkingDaysController extends Controller
{
    private $garageWorkingDaysRepo;
    private $garageRepo;

    public function __construct(GarageWorkingDaysRepository $garageWorkingDaysRepository, GarageRepository $garageRepository)
    {
        $this->garageWorkingDaysRepo = $garageWorkingDaysRepository;
        $this->garageRepo = $garageRepository;
    }
	
	public function editGarageWorkingDays(Request $request)
    {
		Validator::make($request->all(), [
            'garage_working_hours' => 'required|array',
        ])->validate();

		$technician_id = $request->user()->id;
		$garage = $this->garageRepo->getGarageByTechnician($technician_id);
		$this->garageWorkingDaysRepo->updateDaysAndHours($garage->id, $request['garage_working_hours']);
    }

}
