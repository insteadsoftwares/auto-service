<?php

namespace App\Repositories\GarageWorkingDays;

use App\Models\GarageWorkingDays;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Repositories\GarageWorkingHours\GarageWorkingHoursRepository;

class EloquentGarageWorkingDays implements GarageWorkingDaysRepository
{
    /**
     * @var GarageWorkingDays
     */
    private $model;
    private $garageWorkingHoursRepo;

    public function __construct(GarageWorkingDays $model, GarageWorkingHoursRepository $garageWorkingHoursRepository)
    {
        $this->model = $model;
        $this->garageWorkingHoursRepo = $garageWorkingHoursRepository;
    }

	/**
     * Create Garage working days
     *
     * @param $garage_id: int
	 * @return GarageWorkingDays
     */
    public function createGarageWorkingDays($garage_id)
    {
		for ($day = 0; $day <= 6; $day++) {
			$workingDay = GarageWorkingDays::create([
				'garage_id' => $garage_id,
				'day_of_week' => $day,
				'is_open' => true
			]);

			$this->garageWorkingHoursRepo->createGarageWorkingHours($garage_id, $workingDay->id, '08:00:00', '18:00:00');
		}
    }

	/**
     * Edit Garage working days
     *
     * @param $garage_id: int
     * @param $daysData: array
	 * @return GarageWorkingDays
     */
	public function updateDaysAndHours($garage_id, $daysData)
    {
        foreach ($daysData as $dayData) {
			$this->garageWorkingHoursRepo->deleteByDay($dayData['id']);
			foreach ($dayData['garage_working_hours'] as $hour) {
				$this->garageWorkingHoursRepo->createGarageWorkingHours(
					$garage_id,
					$dayData['id'],
					$hour['start_time'],
					$hour['end_time']
				);
			}
        }
    }

}
