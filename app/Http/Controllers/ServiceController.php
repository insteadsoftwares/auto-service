<?php

namespace App\Http\Controllers;

use App\Repositories\Service\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;

class ServiceController extends Controller
{
    private $serviceRepo;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepo = $serviceRepository;
    }

	/**
     * Get paginated list of service.
     *
     * @param Request $request
     * @return object Paginated service list with total count
     */
	public function getServices(Request $request)
    {
        return $this->serviceRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc']);
    }

	/**
     * Create a new service.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function addService(Request $request)
    {
		Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
			'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ])->validate();
		

		$image = $request->file('image');
		$imageName = $image->getClientOriginalName();
		$service = $this->serviceRepo->create($request['name'], $request['description'], $image, $imageName);

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie',
            'service' => $service
        ], 201);
    }

	/**
     * Edit a service.
     *
     * @param Request $request
     */
	public function editService(Request $request)
    {
		$service = $this->serviceRepo->getById($request['id']);
		Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
			'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ])->validate();
		

		$image = $request->file('image');
		$imageName = $image ? $image->getClientOriginalName() : $service->image;
		$service = $this->serviceRepo->edit($service, $request['name'], $request['description'], $image, $imageName);

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie',
            'service' => $service
        ], 201);
    }

	/**
     * Delete service.
     *
     * @param Request $request
     */
    public function deleteService(Request $request)
    {
        return $this->serviceRepo->delete($request['id']);
    }
}
