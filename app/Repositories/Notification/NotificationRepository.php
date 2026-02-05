<?php

namespace App\Repositories\Notification;

interface NotificationRepository
{

    /**
     * Creates a notification
     *
     * @param $appointment_id: int
     * @param $update_user_id: int
     * @param $title: string
     * @param $message: string
     * @return Notification
     */
    public function create($appointment_id, $update_user_id, $title, $message);

    /**
     * Find client notification
     *
     * @param $client_id: int
     * @return Notification[]
     */
    public function getClientNotifications($client_id);

    /**
     * Find technician notification
     *
     * @param $technician_id: int
     * @return Notification[]
     */
    public function getTechnicianNotifications($technician_id);

	/**
     * Mark a notification as read
     *
     * @param $technician_id: int
     */
	public function markAsRead($notification_id);

}
