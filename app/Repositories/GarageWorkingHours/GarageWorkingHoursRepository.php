<?php

namespace App\Repositories\GarageWorkingHours;

interface GarageWorkingHoursRepository
{

	/**
     * Create Garage working days
     *
     * @param $garage_id: int
     * @param $working_day_id: int
     * @param $start_time: int
     * @param $end_time: int
	 * @return GarageWorkingHours
     */
    public function createGarageWorkingHours($garage_id, $working_day_id, $start_time, $end_time);
	
	/**
     * Delete by working day
     *
     * @param $working_day_id: int
     */
    public function deleteByDay($working_day_id);
	
}
