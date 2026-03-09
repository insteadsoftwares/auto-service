<?php

namespace App\Repositories\GarageWorkingHours;

use App\Models\GarageWorkingHours;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentGarageWorkingHours implements GarageWorkingHoursRepository
{
    /**
     * @var GarageWorkingHours
     */
    private $model;

    public function __construct(GarageWorkingHours $model)
    {
        $this->model = $model;
    }

	/**
     * Create Garage working days
     *
     * @param $garage_id: int
     * @param $working_day_id: int
     * @param $start_time: int
     * @param $end_time: int
	 * @return GarageWorkingHours
     */
    public function createGarageWorkingHours($garage_id, $working_day_id, $start_time, $end_time)
    {
		$workingDay = GarageWorkingHours::create([
			'garage_id' => $garage_id,
			'working_day_id' => $working_day_id,
			'start_time' => $start_time,
			'end_time' => $end_time
		]);
    }

	/**
     * Delete by working day
     *
     * @param $working_day_id: int
     */
    public function deleteByDay($working_day_id)
    {
		GarageWorkingHours::where('working_day_id', $working_day_id)->delete();
    }

}
