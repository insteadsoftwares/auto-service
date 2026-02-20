<?php

namespace App\Http\Controllers;

use App\Repositories\Appointment\AppointmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    private $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepo = $appointmentRepository;
    }

	/**
     * Create a new appointment.
     *
     * @param Request $request
     */
	public function addAppointment(Request $request)
    {
        Validator::make($request->all(), [
			'is_client' => 'required|boolean',
			'client_id' => 'required_if:is_client,true|nullable|integer',
            'garage_id' => 'required|int',
            'service_id' => 'required|int',
            'vehicle_id' => 'required_if:is_client,true|nullable|int',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
			'guest_name' => 'required_if:is_client,false|nullable|string',
			'guest_phone' => 'required_if:is_client,false|nullable|string',
			'guest_vehicle_type_id' => 'required_if:is_client,false|nullable|int',
			'guest_vehicle_brand_id' => 'required_if:is_client,false|nullable|int',
			'guest_vehicle_model_id' => 'required_if:is_client,false|nullable|int',
        ])->validate();
		
		$client_id = $request->boolean('is_client') ? $request['client_id'] : null;
        return $this->appointmentRepo->create($client_id, $request['garage_id'], $request['service_id'], $request['vehicle_id'], $request['appointment_date'], 
			$request['appointment_time'], $request['guest_name'], $request['guest_phone'], $request['guest_vehicle_type_id'], $request['guest_vehicle_brand_id'], 
			$request['guest_vehicle_model_id'], $request['is_client']);
    }
	
	/**
     * Edit a appointment status.
     *
     * @param Request $request
     */
    public function editAppointmentStatus(Request $request)
    {
        $appointment = $this->appointmentRepo->getById($request['appointment_id']);

        Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
        ])->validate();
		
        if($request['status'] != $appointment->status) return $this->appointmentRepo->editStatus($appointment, $request['status'], $request->user()->id);
    }
	
	/**
     * Find upcoming client appointments.
     *
     * @param Request $request
     */
    public function getUpcomingClientAppointments(Request $request)
    {
        $client_id = $request->user()->id;
        return $this->appointmentRepo->getUpcomingClientAppointments($client_id);
    }
	
	/**
     * Find expired client appointments.
     *
     * @param Request $request
     */
    public function getExpiredClientAppointments(Request $request)
    {
        $client_id = $request->user()->id;
        return $this->appointmentRepo->getExpiredClientAppointments($client_id);
    }
	
	/**
     * Find upcoming technician appointments.
     *
     * @param Request $request
     */
    public function getUpcomingTechnicianAppointments(Request $request)
    {
        $technician_id = $request->user()->id;
        return $this->appointmentRepo->getUpcomingTechnicianAppointments($technician_id);
    }
	
	/**
     * Find expired technician appointments.
     *
     * @param Request $request
     */
    public function getExpiredTechnicianAppointments(Request $request)
    {
        $technician_id = $request->user()->id;
        return $this->appointmentRepo->getExpiredTechnicianAppointments($technician_id);
    }
	
	/**
     * Find appointments by service.
     *
     * @param Request $request
     */
    public function getAppointmentsByService(Request $request)
    {
        return $this->appointmentRepo->getAppointmentsByService($request['service_id']);
    }
	
	/**
     * Find upcoming garage appointments.
     *
     * @param Request $request
     */
    public function getUpcomingGarageAppointments(Request $request)
    {
        return $this->appointmentRepo->getUpcomingGarageAppointments($request['garage_id']);
    }
	
	/**
     * Edit a appointment.
     *
     * @param Request $request
     */
    public function editAppointment(Request $request)
    {
        $appointment = $this->appointmentRepo->getById($request['id']);
		$appointmentDateTime = $appointment->appointment_date->copy()->setTimeFromTimeString($appointment->appointment_time);

		if (now()->diffInHours($appointmentDateTime, false) < 24) {
			return response()->json([
				'message' => 'Impossible de modifier un rendez-vous moins de 24 heures avant.'
			], 403);
		}
		
        Validator::make($request->all(), [
            'garage_id' => 'required|int',
            'service_id' => 'required|int',
            'vehicle_id' => 'required|int',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
        ])->validate();
		
        return $this->appointmentRepo->edit($appointment, $request['garage_id'], $request['service_id'], $request['vehicle_id'], $request['appointment_date'], $request['appointment_time']);
    }
	
	/**
     * Find appointment by id.
     *
     * @param Request $request
     */
    public function getAppointmentById(Request $request)
    {
        return $this->appointmentRepo->getById($request['id']);
    }
	
	/**
     * Find cancel appointment.
     *
     * @param Request $request
     */
    public function cancelAppointment(Request $request)
    {
        $appointment = $this->appointmentRepo->getById($request['id']);
        if('cancelled' != $appointment->status) return $this->appointmentRepo->editStatus($appointment, 'cancelled', $request->user()->id);
    }

}
