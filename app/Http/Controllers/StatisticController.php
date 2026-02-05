<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Appointment\AppointmentRepository;
use App\Repositories\Garage\GarageRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\User\UserRepository;

class StatisticController extends Controller
{
	private $appointmentRepo;
    private $garageRepo;
    private $serviceRepo;
    private $userRepo;

    public function __construct(AppointmentRepository $appointmentRepository, 
								GarageRepository $garageRepository,
								ServiceRepository $serviceRepository,
								UserRepository $userRepository)
    {
        $this->appointmentRepo = $appointmentRepository;
        $this->garageRepo = $garageRepository;
        $this->serviceRepo = $serviceRepository;
        $this->userRepo = $userRepository;
    }

	/**
     * Find statistics.
     *
     * @param Request $request
     */
    public function getStatistics(Request $request)
    {
        $completedAppointmentNumber = $this->appointmentRepo->getAppointmentNumberByStatus('completed');
        $garagesNumber = $this->garageRepo->getGaragesNumber();
        $servicesNumber = $this->serviceRepo->getServicesNumber();
        $clientsNumber = $this->userRepo->getCLientNumber();

		return (object) ['completedAppointments' => $completedAppointmentNumber, 'garages' => $garagesNumber, 'services' => $servicesNumber, 'clients' => $clientsNumber];
    }

	/**
	 * Get total and completed appointments.
	 *
	 * @param Request $request
	 */
	public function getAppointmentStatistics(Request $request)
	{
		$totalAppointments = $this->appointmentRepo->getTotalAppointmentNumber();
		$completedAppointments = $this->appointmentRepo->getAppointmentNumberByStatus('completed');

		return (object) [
			'totalAppointments' => $totalAppointments,
			'completedAppointments' => $completedAppointments,
		];
	}

	/**
	 * Get six best garages.
	 *
	 * @param Request $request
	 */
	public function getSixBestGarages(Request $request)
	{
		$sixBestGarages = $this->garageRepo->getSixBestGarages((int)$request['service_id']);
		return $sixBestGarages;
	}

	/**
	 * Get Three best services.
	 *
	 * @param Request $request
	 */
	public function getThreeBestServices(Request $request)
	{
		$threeBestServices = $this->serviceRepo->getThreeBestServices();
		return $threeBestServices;
	}

	/**
	 * Get appointments status.
	 *
	 * @param Request $request
	 */
	public function getAppointmentsStatus(Request $request)
	{
		$completedAppointments = $this->appointmentRepo->getAppointmentNumberByStatus('completed');
		$pendingAppointments = $this->appointmentRepo->getAppointmentNumberByStatus('pending');
		$confirmedAppointments = $this->appointmentRepo->getAppointmentNumberByStatus('confirmed');
		$cancelledAppointments = $this->appointmentRepo->getAppointmentNumberByStatus('cancelled');

		return (object) [
			'pendingAppointments' => $pendingAppointments,
			'completedAppointments' => $completedAppointments,
			'confirmedAppointments' => $confirmedAppointments,
			'cancelledAppointments' => $cancelledAppointments,
		];
	}

	/**
     * Find statistics.
     *
     * @param Request $request
     */
    public function getGarageStatistics(Request $request)
    {
        $technician_id = $request->user()->id;
		$completedAppointments = $this->appointmentRepo->getAppointmentNumberByStatusAndTechnician('completed', $technician_id);
		$pendingAppointments = $this->appointmentRepo->getUpcomingAppointmentNumberByStatusAndTechnician('pending', $technician_id);
		$upcomingAppointments = $this->appointmentRepo->getUpcomingAppointmentNumberByStatusAndTechnician('confirmed', $technician_id);

		return (object) ['completedAppointments' => $completedAppointments, 'pendingAppointments' => $pendingAppointments, 'upcomingAppointments' => $upcomingAppointments];
    }

	/**
     * Find appointments evolution by Date.
     *
     * @param Request $request
     */
    public function getAppointmentsEvolutionByDate(Request $request)
    {
        $technician_id = $request->user()->id;
		return $this->appointmentRepo->getAppointmentsEvolutionByDate($technician_id, $request['start_date'], $request['end_date'], $request['status']);
    }

	/**
     * Find appointments evolution by Date.
     *
     * @param Request $request
     */
    public function getAppointmentsCountByService(Request $request)
    {
        $technician_id = $request->user()->id;
		return $this->appointmentRepo->getAppointmentsCountByService($technician_id, $request['start_date'], $request['end_date']);
    }

	/**
     * Find Three best clients 
     *
     * @param Request $request
     */
    public function getThreeBestClients(Request $request)
    {
        $technician_id = $request->user()->id;
		return $this->appointmentRepo->getThreeBestClients($technician_id);
    }

}
