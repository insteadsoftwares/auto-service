<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Repositories\Notification\NotificationRepository;

class EloquentAppointment implements AppointmentRepository
{
    /**
     * @var Appointment
     */
    private $model;
	private $notificationRepo;

    public function __construct(Appointment $model, NotificationRepository $notificationRepository)
    {
        $this->model = $model;
		$this->notificationRepo = $notificationRepository;
    }

    /**
     * Creates a appointment
     *
     * @param $client_id: int
     * @param $garage_id: array
     * @param $service_id: array
     * @param $vehicle_id: array
     * @param $appointment_date: date
     * @param $appointment_time: time
     * @return Appointment
     */
    public function create($client_id, $garage_id, $service_id, $vehicle_id, $appointment_date, $appointment_time)
    {
        $createdAppointment = Appointment::create([
            'client_id' => $client_id,
            'garage_id' => $garage_id,
            'service_id' => $service_id,
            'vehicle_id' => $vehicle_id,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'status' => 'pending',
        ]);
		$this->notificationRepo->create($createdAppointment->id, $client_id, 'new appointment', null);

        return $createdAppointment;
    }

	/**
     * Finds appointment by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return Appointment
     */
    public function getById($id, $relations = [])
    {
        return $this->model::with($relations)->find($id);
    }

    /**
     * Edit appointment status
     *
     * @param $appointment: Appointment
     * @param $status: string
     * @param $user_id: int
     * @return Appointment
     */
    public function editStatus($appointment, $status, $user_id)
    {
		$old_status = $appointment->status;
        $appointment->status = $status;
		$appointment->save();
		$title = '';
		if($appointment->status == 'confirmed') $title = 'appointment confirmed';
		elseif($appointment->status == 'cancelled') $title = 'appointment cancelled';
		elseif($appointment->status == 'completed') $title = 'appointment completed';
		$this->notificationRepo->create($appointment->id, $user_id, $title, null);

        return $appointment;
    }

	/**
     * Find upcoming client appointments
     *
     * @param $client_id: int
     * @return Appointment[]
     */
	public function getUpcomingClientAppointments($client_id)
	{
		$now = Carbon::now();
		return Appointment::where('client_id', $client_id)
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '>', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '>=', $now->toTimeString());
					});
			})
			->orderBy('appointment_date')
			->orderBy('appointment_time')
			->with(['garage', 'service', 'vehicle.vehicleType', 'vehicle.vehicleBrand', 'vehicle.vehicleModele'])
			->get();
	}

	/**
     * Find expired client appointments
     *
     * @param $client_id: int
     * @return Appointment[]
     */
	public function getExpiredClientAppointments($client_id)
	{
		$now = Carbon::now();
		return Appointment::where('client_id', $client_id)
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '<', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '<', $now->toTimeString());
					});
			})
			->orderBy('appointment_date', 'desc')
			->orderBy('appointment_time', 'desc')
			->with(['garage', 'service', 'vehicle.vehicleType', 'vehicle.vehicleBrand', 'vehicle.vehicleModele'])
			->get();
	}

	/**
     * Find upcoming technician appointments
     *
     * @param $technician_id: int
     * @return Appointment[]
     */
	public function getUpcomingTechnicianAppointments($technician_id)
	{
		$now = Carbon::now();
		return Appointment::whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '>', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '>=', $now->toTimeString());
					});
			})
			->orderBy('appointment_date')
			->orderBy('appointment_time')
			->with(['client', 'service', 'vehicle.vehicleType', 'vehicle.vehicleBrand', 'vehicle.vehicleModele'])
			->get();
	}
	
	/**
     * Find expired technician appointments
     *
     * @param $technician_id: int
     * @return Appointment[]
     */
	public function getExpiredTechnicianAppointments($technician_id)
	{
		$now = Carbon::now();
		return Appointment::whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '<', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '>=', $now->toTimeString());
					});
			})
			->orderBy('appointment_date')
			->orderBy('appointment_time')
			->with(['client', 'service', 'vehicle.vehicleType', 'vehicle.vehicleBrand', 'vehicle.vehicleModele'])
			->get();
	}

	/**
     * Find appointments by service
     *
     * @param $service_id: int
     * @return Appointment[]
     */
	public function getAppointmentsByService($service_id)
	{
		return Appointment::where('service_id', $service_id)->get();
	}

	/**
     * Find appointments by garage
     *
     * @param $garage_id: int
     * @return Appointment[]
     */
	public function getUpcomingGarageAppointments($garage_id)
	{
		$now = Carbon::now();
		return Appointment::where('garage_id', $garage_id)
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '>', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '>=', $now->toTimeString());
					});
			})
			->get();
	}
	
	/**
     * Edit a appointment
     *
     * @param $appointment: Appointment
     * @param $garage_id: int
     * @param $service_id: int
     * @param $vehicle_id: int
     * @param $appointment_date: date
     * @param $appointment_time: time
     * @return Appointment
     */
	public function edit($appointment, $garage_id, $service_id, $vehicle_id, $appointment_date, $appointment_time)
    {
		$oldStatus = $appointment->status;
		$appointment->garage_id = $garage_id;
		$appointment->service_id = $service_id;
		$appointment->vehicle_id = $vehicle_id;
		$appointment->appointment_date = $appointment_date;
		$appointment->appointment_time = $appointment_time;
		$appointment->status = 'pending';
		$appointment->save();
		$this->notificationRepo->create($appointment->id, $appointment->client_id, 'Appointment updated', null);

		return $appointment;
    }

	/**
	 * Get the number of appointments by status
	 *
	 * @param $status
	 * @return int
	 */
	public function getAppointmentNumberByStatus($status)
	{
		return $this->model::where('status', $status)->count();
	}

	/**
	 * Get the number appointments.
	 *
	 * @return int
	 */
	public function getTotalAppointmentNumber()
	{
		return $this->model::count();
	}

	/**
	 * Get the number of appointments by status and technician
	 *
	 * @param $technician_id
	 * @param $status
	 * @return int
	 */
	public function getAppointmentNumberByStatusAndTechnician($status, $technician_id)
	{
		return $this->model::where('status', $status)
			->whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})->count();
	}
	
	/**
	 * Get the number of upcoming appointments by status and technician
	 *
	 * @param $status
	 * @param $technician_id
	 * @return int
	 */
	public function getUpcomingAppointmentNumberByStatusAndTechnician($status, $technician_id)
	{
		$now = Carbon::now();
		return $this->model::where('status', $status)
			->whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->where(function ($query) use ($now) {
				$query->where('appointment_date', '>', $now->toDateString())
					->orWhere(function ($q) use ($now) {
						$q->where('appointment_date', $now->toDateString())
							->where('appointment_time', '>=', $now->toTimeString());
					});
			})->count();
	}
	
	/**
	 * Find appointments evolution by Date
	 *
	 * @param $technician_id
	 * @param $start_date
	 * @param $end_date
	 * @param $status
	 * @return Array
	 */
	public function getAppointmentsEvolutionByDate($technician_id, $start_date, $end_date, $status) 
	{
        $query = Appointment::selectRaw('appointment_date, COUNT(*) as total_rdv')
            ->whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
            ->whereBetween('appointment_date', [$start_date, $end_date]);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        return $query
            ->groupBy('appointment_date')
            ->orderBy('appointment_date')
            ->get();
    }
	
	/**
	 * Find the number of appointments by date service
	 *
	 * @param $technician_id
	 * @param $start_date
	 * @param $end_date
	 * @return Array
	 */
	public function getAppointmentsCountByService($technician_id, $start_date, $end_date) 
	{
        $query = Appointment::selectRaw('service_id, COUNT(*) as total_rdv')
			->whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->whereBetween('appointment_date', [$start_date, $end_date])
			->groupBy('service_id')
			->with(['service']);

		return $query->get();
    }

	/**
	 * Find Three best clients
	 *
	 * @param $technician_id
	 * @return Array
	 */
	public function getThreeBestClients($technician_id) 
	{
		return Appointment::selectRaw('client_id, COUNT(*) as total_rdv')
			->whereHas('garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->where('status', 'completed')
			->groupBy('client_id')
			->orderByDesc('total_rdv')
			->with(['client'])
			->limit(3)
			->get();
	}

}
