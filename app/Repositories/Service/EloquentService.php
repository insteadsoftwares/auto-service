<?php

namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentService implements ServiceRepository
{
    /**
     * @var Service
     */
    private $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

	/**
     * paginates Service
     *
     * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
     * @return Service[]
     */
	public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc)
	{
		$query = $this->model->newQuery();

		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('name', 'LIKE', '%' . $searchQuery . '%')
				->orWhere('description', 'LIKE', '%' . $searchQuery . '%');
			});
		}

		$total = $query->count();
		if ($sortBy !== '' && $sortBy !== null) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }
        if ($page !== null && $perPage !== null) {
            $query->skip(($page - 1) * $perPage)->take($perPage);
        }

		return (object) [
            'nodes' => $query->get(),
            'total' => $total,
        ];
	}

    /**
     * Creates a service
     *
     * @param $name: string
     * @param $description: string
     * @param $image: string
     * @param $imageName: string
     * @return Service
     */
    public function create($name, $description, $image, $imageName)
    {
		$image->move(public_path('homePage/img/service'), $imageName);
        $createdService = Service::create([
            'name' => $name,
            'description' => $description,
            'image' => $imageName,
        ]);

        return $createdService;
    }

    /**
     * Finds service by id.
     *
     * @return Service
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

	/**
     * Edit a service
     *
     * @param $service: service
     * @param $name: string
     * @param $description: string
     * @param $image: string
     * @param $imageName: string
     * @return Service
     */
	public function edit($service, $name, $description, $image, $imageName)
    {
		$oldImage = $service->image;

		if ($image) {
			$image->move(public_path('homePage/img/service'), $imageName);
			if ($oldImage && file_exists(public_path('homePage/img/service/' . $oldImage))) {
				unlink(public_path('homePage/img/service/' . $oldImage));
			}
			$service->image = $imageName;
		}

		$service->name = $name;
		$service->description = $description;
		$service->save();

		return $service;
    }

    /**
     * Deletes service
     *
     * @param $service_id: int
     */
    public function delete($service_id)
    {
        $service = $this->getById($service_id);
        return $service->delete();
    }

	/**
     * Find services number.
     *
	 * @return int
     */
    public function getServicesNumber()
    {
        return $this->model::where('deleted_at', null)->count();
    }

	/**
     * Find three best services.
     *
	 * @return Object
     */
    public function getThreeBestServices()
    {
        $topServices = $this->model::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(3)
            ->get();

        $chartData = $topServices->map(function($service) {
			return [
				'service' => $service,
				'appointments_nb' => $service->appointments_count,
			];
		});

		return $chartData;
    }

}
