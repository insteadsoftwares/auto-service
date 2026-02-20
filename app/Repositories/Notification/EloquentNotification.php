<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EloquentNotification implements NotificationRepository
{
    /**
     * @var Notification
     */
    private $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    /**
     * Creates a notification
     *
     * @param $appointment_id: int
     * @param $update_user_id: int
     * @param $title: string
     * @param $message: string
     * @return Notification
     */
    public function create($appointment_id, $update_user_id, $title, $message)
    {
        $createdNotification = Notification::create([
            'appointment_id' => $appointment_id,
            'title' => $title,
            'message' => $message,
            'update_user_id' => $update_user_id
        ]);

        return $createdNotification;
    }

    /**
     * Find client notification
     *
     * @param $client_id: int
     * @return Notification[]
     */
    public function getClientNotifications($client_id)
    {
		$unreadNotifications = Notification::whereHas('appointment', function ($query) use ($client_id) {
				$query->where('client_id', $client_id);
			})
			->where('update_user_id', '!=', $client_id)
			->where('is_read', false)
			->with(['appointment.client', 'appointment.service'])
			->orderBy('created_at', 'desc')
    		->get();

		if ($unreadNotifications->count() < 10) {
			$readNotifications = Notification::whereHas('appointment', function ($query) use ($client_id) {
					$query->where('client_id', $client_id);
				})
				->where('update_user_id', '!=', $client_id)
				->where('is_read', true)
				->with(['appointment.client', 'appointment.service'])
				->orderBy('created_at', 'desc')
				->take(10 - $unreadNotifications->count())
				->get();

			$notifications = $unreadNotifications->merge($readNotifications);
		} else {
			$notifications = $unreadNotifications;
		}
		return $notifications;
    }

    /**
     * Find technician notification
     *
     * @param $technician_id: int
     * @return Notification[]
     */
    public function getTechnicianNotifications($technician_id)
    {
		$unreadNotifications = Notification::whereHas('appointment.garage', function ($query) use ($technician_id) {
				$query->where('technician_id', $technician_id);
			})
			->where(function($query) use ($technician_id) {
				$query->where('update_user_id', '!=', $technician_id)
					->orWhereNull('update_user_id');
			})
			->where('is_read', false)
			->with(['appointment.garage', 'appointment.client', 'appointment.service'])
			->orderBy('created_at', 'desc')
    		->get();

		if ($unreadNotifications->count() < 10) {
			$readNotifications = Notification::whereHas('appointment.garage', function ($query) use ($technician_id) {
					$query->where('technician_id', $technician_id);
				})
				->where(function($query) use ($technician_id) {
					$query->where('update_user_id', '!=', $technician_id)
						->orWhereNull('update_user_id');
				})
				->where('is_read', true)
				->with(['appointment.garage', 'appointment.client', 'appointment.service'])
				->orderBy('created_at', 'desc')
				->take(10 - $unreadNotifications->count())
				->get();

			$notifications = $unreadNotifications->merge($readNotifications);
		} else {
			$notifications = $unreadNotifications;
		}
		return $notifications;
    }

	/**
     * Mark a notification as read
     *
     * @param $technician_id: int
     */
	public function markAsRead($notification_id)
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->is_read = true;
        $notification->save();
		return $notification;
    }
	
}
