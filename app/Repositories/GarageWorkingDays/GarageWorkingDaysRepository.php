<?php

namespace App\Repositories\GarageWorkingDays;

interface GarageWorkingDaysRepository
{

	/**
     * Create Garage working days
     *
     * @param $garage_id: int
	 * @return GarageWorkingDays
     */
    public function createGarageWorkingDays($garage_id);

	/**
     * Edit Garage working days
     *
     * @param $garage_id: int
     * @param $daysData: array
	 * @return GarageWorkingDays
     */
	public function updateDaysAndHours($garage_id, $daysData);

}
