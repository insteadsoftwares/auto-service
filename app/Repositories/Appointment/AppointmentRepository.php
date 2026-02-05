<?php

namespace App\Repositories\Appointment;

interface AppointmentRepository
{
	
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
    public function create($client_id, $garage_id, $service_id, $vehicle_id, $appointment_date, $appointment_time);

	/**
     * Finds appointment by id.
     *
     * @param $id: int
     * @param $relations: array
     * @return Appointment
     */
    public function getById($id, $relations = []);

    /**
     * Edit appointment status
     *
     * @param $appointment: Appointment
     * @param $status: string
     * @param $user_id: int
     * @return Appointment
     */
    public function editStatus($appointment, $status, $user_id);

	/**
     * Find upcoming client appointments
     *
     * @param $client_id: int
     * @return  Appointment[]
     */
	public function getUpcomingClientAppointments($client_id);

	/**
     * Find expired client appointments
     *
     * @param $client_id: int
     * @return Appointment[]
     */
	public function getExpiredClientAppointments($client_id);
	
	/**
     * Find upcoming technician appointments
     *
     * @param $technician_id: int
     * @return Appointment[]
     */
	public function getUpcomingTechnicianAppointments($technician_id);
	
	/**
     * Find expired technician appointments
     *
     * @param $technician_id: int
     * @return Appointment[]
     */
	public function getExpiredTechnicianAppointments($technician_id);

	/**
     * Find appointments by service
     *
     * @param $service_id: int
     * @return Appointment[]
     */
	public function getAppointmentsByService($service_id);

	/**
     * Find appointments by garage
     *
     * @param $garage_id: int
     * @return Appointment[]
     */
	public function getUpcomingGarageAppointments($garage_id);
	
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
	public function edit($appointment, $garage_id, $service_id, $vehicle_id, $appointment_date, $appointment_time);
	
	/**
	 * Get the number of appointments by status.
	 *
	 * @param $status
	 * @return int
	 */
	public function getAppointmentNumberByStatus($status);

	/**
	 * Get the number appointments.
	 *
	 * @return int
	 */
	public function getTotalAppointmentNumber();

	/**
	 * Get the number of appointments by status and technician
	 *
	 * @param $technician_id
	 * @param $status
	 * @return int
	 */
	public function getAppointmentNumberByStatusAndTechnician($status, $technician_id);

	/**
	 * Get the number of upcoming appointments by status and technician
	 *
	 * @param $status
	 * @param $technician_id
	 * @return int
	 */
	public function getUpcomingAppointmentNumberByStatusAndTechnician($status, $technician_id);
	
	/**
	 * Find appointments evolution by Date
	 *
	 * @param $technician_id
	 * @param $start_date
	 * @param $end_date
	 * @param $status
	 * @return Array
	 */
	public function getAppointmentsEvolutionByDate($technician_id, $start_date, $end_date, $status);
	
	/**
	 * Find the number of appointments by date service
	 *
	 * @param $technician_id
	 * @param $start_date
	 * @param $end_date
	 * @return Array
	 */
	public function getAppointmentsCountByService($technician_id, $start_date, $end_date);

	/**
	 * Find Three best clients 
	 *
	 * @param $technician_id
	 * @return Array
	 */
	public function getThreeBestClients($technician_id);

}
