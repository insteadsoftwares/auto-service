<?php

namespace App\Repositories\GarageLeave;

use App\Models\GarageLeave;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Repositories\Appointment\AppointmentRepository;
use Carbon\Carbon;

class EloquentGarageLeave implements GarageLeaveRepository
{
    /**
     * @var GarageLeave
     */
    private $model;
    private $appointmentRepo;

    public function __construct(GarageLeave $model, AppointmentRepository $appointmentRepository)
    {
        $this->model = $model;
        $this->appointmentRepo = $appointmentRepository;
    }

	/**
     * Deletes Garage Leave
     *
     * @param $garage_leave: GarageLeave
     */
    public function delete($garage_leave)
    {
        return $garage_leave->delete();
    }

	/**
     * Create Garage Leave
     *
     * @param $garage_id: int
     * @param $start_date: date
     * @param $end_date: date
     * @param $user_id: int
	 * @return GarageLeave
     */
    public function createGarageLeaves($garage_id, $start_date, $end_date, $user_id)
    {
        $garageLeave = GarageLeave::create([
			'garage_id' => $garage_id,
			'start_date' => $start_date,
			'end_date' => $end_date,
		]);

		$appointments = $this->appointmentRepo->getAppointmentsNotCancelledByGarageAndBetweenTwoDates($garage_id, $start_date, $end_date);
		foreach ($appointments as $appointment) {
			$this->appointmentRepo->editStatus($appointment, 'cancelled', $user_id);
		}

		return $garageLeave;
    }

	/**
     * Remove Leave days
     *
     * @param $garage_id: int
     * @param $start_date: date
     * @param $end_date: date
     */
	public function removeLeaveDayS($garage_id, $start_date, $end_date)
	{
		$removeStart = Carbon::parse($start_date);
		$removeEnd   = Carbon::parse($end_date);

		$leaves = GarageLeave::where('garage_id', $garage_id)
			->where('start_date', '<=', $removeEnd)
			->where('end_date', '>=', $removeStart)
			->get();

		foreach ($leaves as $leave) {

			$originalStart = Carbon::parse($leave->start_date);
			$originalEnd   = Carbon::parse($leave->end_date);

			// Delete at the beginning
			if ($removeStart->lte($originalStart) && $removeEnd->gte($originalEnd)) {
				$leave->delete();
				continue;
			}

			// Delete at the beginning
			if ($removeStart->lte($originalStart) && $removeEnd->lt($originalEnd)) {
				$leave->start_date = $removeEnd->copy()->addDay();
				$leave->save();
				continue;
			}

			// Delete at the end
			if ($removeStart->gt($originalStart) && $removeEnd->gte($originalEnd)) {
				$leave->end_date = $removeStart->copy()->subDay();
				$leave->save();
				continue;
			}

			// Delete in the middle
			if ($removeStart->gt($originalStart) && $removeEnd->lt($originalEnd)) {
				GarageLeave::create([
					'garage_id' => $garage_id,
					'start_date' => $originalStart,
					'end_date' => $removeStart->copy()->subDay(),
				]);

				GarageLeave::create([
					'garage_id' => $garage_id,
					'start_date' => $removeEnd->copy()->addDay(),
					'end_date' => $originalEnd,
				]);

				$leave->delete();
			}
		}
	}
	
	/**
     * Find garage Leaves
     *
     * @param $garage_id: int
	 * @return GarageLeave
     */
	public function getGarageLeaves($garage_id){
		return GarageLeave::where('garage_id', $garage_id)->get();
	}

}
