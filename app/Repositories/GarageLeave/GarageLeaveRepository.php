<?php

namespace App\Repositories\GarageLeave;

interface GarageLeaveRepository
{

	/**
     * Deletes Garage Leave
     *
     * @param $garage_leave: GarageLeave
     */
    public function delete($garage_leave);

	/**
     * Create Garage Leave
     *
     * @param $garage_id: int
     * @param $start_date: date
     * @param $end_date: date
     * @param $user_id: int
	 * @return GarageLeave
     */
    public function createGarageLeaves($garage_id, $start_date, $end_date, $user_id);

	/**
     * Remove Leave days
     *
     * @param $garage_id: int
     * @param $start_date: date
     * @param $end_date: date
     */
	public function removeLeaveDayS($garage_id, $start_date, $end_date);

	/**
     * Find garage Leaves
     *
     * @param $garage_id: int
	 * @return GarageLeave
     */
	public function getGarageLeaves($garage_id);

}
