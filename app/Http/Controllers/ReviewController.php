<?php

namespace App\Http\Controllers;

use App\Repositories\Review\ReviewRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;

class ReviewController extends Controller
{
    private $reviewRepo;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepo = $reviewRepository;
    }

	/**
     * Create a new Review.
     *
     * @param Request $request
     */
	public function addReview(Request $request)
    {
		Validator::make($request->all(), [
            'appointment_id' => 'required|int',
            'rating' => 'required|int',
            'comment' => 'required|string',
        ])->validate();
		
		$client_id = $request->user()->id;
		return $this->reviewRepo->createReview($client_id, $request['appointment_id'], $request['rating'], $request['comment']);
    }

	/**
     * Find Reviews.
     *
     * @param Request $request
     * @return object Paginated service list with total count
     */
	public function getReviews(Request $request)
    {
        return $this->reviewRepo->searchAndPaginate($request['searchQuery'], $request['perPage'], $request['page'], $request['sortBy'], $request['sortDesc']);
    }

	/**
     * Delete Review.
     *
     * @param Request $request
     */
    public function deleteReview(Request $request)
    {
        return $this->reviewRepo->delete($request['id']);
    }
}