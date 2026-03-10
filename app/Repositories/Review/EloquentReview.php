<?php

namespace App\Repositories\Review;

use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EloquentReview implements ReviewRepository
{

    /**
     * @var Review
     */
    private $model;

    public function __construct(Review $model)
    {
        $this->model = $model;
    }

	/**
     * Create Review
     *
     * @param $client_id: int
     * @param $appointment_id: int
     * @param $rating: int
     * @param $comment: string
	 * @return Review
     */
    public function createReview($client_id, $appointment_id, $rating, $comment)
    {
		$workingDay = Review::create([
			'client_id' => $client_id,
			'appointment_id' => $appointment_id,
			'rating' => $rating,
			'comment' => $comment,
		]);
    }

	/**
     * Find Reviews
     *
	 * @return Review[]
     */
    public function getAllReviews()
    { 
		return Review::with(['client', 'appointment.service', 'appointment.garage'])->get();
    }
	
	/**
     * paginates reviews
     *
	 * @param $searchQuery: string
     * @param $perPage: integer
     * @param $page: integer
     * @param $sortBy: string
     * @param $sortDesc: boolean
	 * @return Review[]
     */
    public function searchAndPaginate($searchQuery, $perPage, $page, $sortBy, $sortDesc)
	{
		$query = $this->model->newQuery();
		$query->with(['client', 'appointment.service', 'appointment.garage']);
		if ($searchQuery !== '') {
			$query->where(function($q) use ($searchQuery) {
				$q->where('rating', 'LIKE', '%' . $searchQuery . '%')
				->orWhere('comment', 'LIKE', '%' . $searchQuery . '%')

				->orWhereHas('client', function ($q2) use ($searchQuery) {
					$q2->where('first_name', 'LIKE', '%' . $searchQuery . '%')
						->orWhere('last_name', 'LIKE', '%' . $searchQuery . '%');
				})

				->orWhereHas('appointment.service', function ($q3) use ($searchQuery) {
					$q3->where('name', 'LIKE', '%' . $searchQuery . '%');
				})

				->orWhereHas('appointment.garage', function ($q4) use ($searchQuery) {
					$q4->where('name', 'LIKE', '%' . $searchQuery . '%');
				});
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
     * Finds Review by id.
     *
     * @return Review
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Deletes Review
     *
     * @param $review_id: int
     */
    public function delete($review_id)
    {
        $review = $this->getById($review_id);
        return $review->delete();
    }

}
