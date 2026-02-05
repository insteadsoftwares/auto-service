<?php

namespace App\Http\Controllers;

use App\Repositories\Notification\NotificationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class NotificationController extends Controller
{
    private $notificationRepo;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepo = $notificationRepository;
    }
	
	/**
     * Find client notification.
     *
     * @param Request $request
     */
    public function getClientNotifications(Request $request)
    {
        $client_id = $request->user()->id;
        return $this->notificationRepo->getClientNotifications($client_id);
    }
	
	/**
     * Find technician notification.
     *
     * @param Request $request
     */
    public function getTechnicianNotifications(Request $request)
    {
        $technician_id = $request->user()->id;
        return $this->notificationRepo->getTechnicianNotifications($technician_id);
    }
	
	/**
     * Mark a notification as read
     *
     * @param Request $request
     */
    public function markAsRead(Request $request)
    {
        return $this->notificationRepo->markAsRead($request['id']);
    }

}
